<?php

namespace MaximeRainville\GithubAudit\Models;

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
    private static $table_name = 'Repository';

    private static $db = [
        'Name' => 'Varchar(255)',
        'GithubId' => 'Int',
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

    private static $summary_fields = [
        'Name' => 'Name',
        'Organisation.Name' => 'Organisation',
    ];

}
