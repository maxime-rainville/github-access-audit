<?php

namespace MaximeRainville\GithubAudit\Models\GitHub;

use SilverStripe\Forms\FormField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldDataColumns;

class User extends DataObject
{

    private static $table_name = 'GHUser';

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
        'Note' => 'Note'
    ];

    private static $searchable_fields = [
        'Login',
        'AccessReview',
        'Note',
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

        /** @var GridField $gridfield */
        $gridfield = $fields->fieldByName('Root.Repositories.Repositories');
        $gridfield->getConfig()->getComponentByType(GridFieldDataColumns::class)->setDisplayFields([
            'Name' => 'Name',
            'Organisation.Name' => 'Organisation',
            'Join.Roles' => 'Roles',
        ]);

        return $fields;
    }

    public function getRepoAccessSummary()
    {
        $count = $this->RepositoriesCount();
        $top3 = $this->Repositories()->limit(3)->map('ID', 'Name')->toArray();
        if ($count <= 3) {
            return implode(', ', $top3);
        } else {
            return implode(', ', $top3) . ' and ' . ($count - 3) . ' more...';
        }
    }
}

