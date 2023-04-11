<?php

namespace MaximeRainville\GithubAudit\Models\GitHub;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ManyManyThroughList;

/**
 * @method ManyManyThroughList|User[] Users()
 * @property int $GithubId
 * @property string $Name
 * @property int $OrganisationID
 * @property Organisation $Organisation
 */
class Repository extends DataObject
{
    private static $table_name = 'GHRepository';

    private static $db = [
        'Name' => 'Varchar(255)',
        'GithubId' => 'Int',
        'Skip' => 'Boolean',
        'Notes' => 'Text',
    ];

    private static $has_one = [
        'Organisation' => Organisation::class,
    ];

    private static $many_many = [
        'Users' => [
            'through' => RepoUser::class,
            'from' => 'Repository',
            'to' => 'User',
        ]
    ];

    private static $defaults = [
        'Skip' => false,
    ];

    private static $summary_fields = [
        'Name' => 'Name',
        'Organisation.Name' => 'Organisation',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->fieldByName('Root.Main.Skip')->setDescription('Some repos (e.g. Private temporary forks attached to security notices) should not be included in the report.');

        return $fields;
    }

}
