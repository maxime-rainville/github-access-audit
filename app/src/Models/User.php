<?php

namespace MaximeRainville\GithubAudit\Models;

use SilverStripe\Forms\FormField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;

class User extends DataObject
{

    private static $table_name = 'User';

    private static $db = [
        'Login' => 'Varchar(255)',
        'GithubId' => 'Int',
        'AccessReview' => 'Enum("TODO, GOOD, BAD", "TODO")',
        'AvatarUrl' => 'Varchar(255)',
        'Note' => 'Text',
    ];

    private static $belongs_many_many = [
        'Repositories' => Repository::class,
    ];

    private static $summary_fields = [
        'Avatar' => 'Avatar',
        'Login' => 'Login',
        'RepoAccessSummary' => 'Repositories',
        'AccessReview' => 'Access Review',
    ];

    public function Title()
    {
        return $this->Login;
    }

    public function RepositoriesCount(): int
    {
        return $this->Repositories()->Count();
    }

    public function getProfileLink()
    {
        return $this->renderWith(self::class . '_ProfileLink');
    }

    public function getAvatar()
    {
        return $this->renderWith(self::class . '_Avatar');
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $login = ArrayData::create(['Title' => 'Login'])->renderWith(
            FormField::class . '_holder',
            ['Field' => $this->getProfileLink()]
        );

        $fields->replaceField(
            'Login',
            LiteralField::create('GitHub Link', $login)
        );

        $fields->removeByName('AvatarUrl');

        return $fields;
    }

    public function getRepoAccessSummary()
    {
        $count = $this->RepositoriesCount();
        $topFiveRepos = $this->Repositories()->limit(3)->map('ID', 'Name')->toArray();
        if ($count <= 5) {
            return implode(', ', $topFiveRepos);
        } else {
            return implode(', ', $topFiveRepos) . ' and ' . ($count - 3) . ' more...';
        }
    }
}

