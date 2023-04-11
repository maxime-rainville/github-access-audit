<?php

namespace MaximeRainville\GithubAudit\Tasks;

use Exception;
use Generator;
use Github\Client;
use MaximeRainville\GithubAudit\Models\GitHub\Organisation;
use MaximeRainville\GithubAudit\Models\GitHub\Repository;
use MaximeRainville\GithubAudit\Models\GitHub\User;

use SilverStripe\Dev\BuildTask;

class FetchRepos extends BuildTask
{

    private static $segment = 'fetch-repos';

    private array $failed_repos = [];

    public function __construct(private Client $client)
    {
        parent::__construct();
    }

    public function run($request)
    {
        $this->loopOrgs();

        if ($this->failed_repos) {
            echo "\n\nCould not fetch data for the following repositories:\n";
            foreach ($this->failed_repos as $repo) {
                echo "- $repo\n";
            }
        }
    }

    private function loopOrgs(): void
    {
        $orgs = Organisation::get();

        foreach ($orgs as $org) {
            echo "\nFetching Repos for {$org->Name}\n";
            $this->loopRepos($org);
        }
    }

    private function loopRepos(Organisation $org): void
    {
        foreach ($this->repoFetcher($org->Name) as $repoData) {
            echo "- processing {$repoData['name']}\n";
            $repo = $org->Repositories()->filter('Name', $repoData['name'])->first();
            if (!$repo) {
                $repo = Repository::create();
            } elseif ($repo->Skip) {
                continue;
            }

            $repo->Name = $repoData['name'];
            $repo->GithubId = $repoData['id'];
            $repo->OrganisationID = $org->ID;
            $repo->write();
            try {
                $this->addCollaborators($org, $repo);
            } catch (Exception $ex) {
                $repo->Notes = $ex->getMessage() . "\n" . $ex->getTraceAsString();
                echo "REPO FETCHING Failed: {$ex->getMessage()}\n";
                $this->failed_repos[] = $org->Name . '/' . $repo->Name;
                $repo->write();
            }

        }
    }

    private function repoFetcher(string $org): Generator
    {
        $page = 1;
        while ($data = $this->client->organization()->repositories($org, 'all', $page++)) {
            foreach ($data as $repoData) {
                yield $repoData;
            }
            $this->throttle();
        }
        return;
    }

    private function addCollaborators(Organisation $org, Repository $repo): void
    {
        $repo->Users()->removeAll();

        foreach ($this->repoCollab($org->Name, $repo->Name) as $collabData) {
            $user = User::get()->filter('Login', $collabData['login'])->first();
            if (!$user) {
                $user = User::create();
            }

            $user->GithubId = $collabData['id'];
            $user->Login = $collabData['login'];
            $user->AvatarUrl = $collabData['avatar_url'];
            $user->write();
            $repo->Users()->add($user, ['Roles' => implode(',', array_keys(array_filter($collabData['permissions'])))]);
        }
    }

    private function repoCollab(string $org, string $repo): Generator
    {
        $page = 1;
        while ($data = $this->client->repository()->collaborators()->all($org, $repo, ['page' => $page++])) {
            foreach ($data as $repoData) {
                yield $repoData;
            }
            $this->throttle();
        }
        return;
    }

    private function throttle(): void
    {
        $limit = $this->client->rateLimit()->getResource('core');
        while ($limit->getRemaining() < 100) {
            $sleepFor = $limit->getReset() - time();
            echo "\n\nSleeping for {$sleepFor} seconds\n";
            time_sleep_until($limit->getReset());
            $limit = $this->client->rateLimit()->getResource('core');
        }
    }
}
