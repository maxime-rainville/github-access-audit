<?php

namespace MaximeRainville\GithubAudit\Models\GitHub;

use SilverStripe\ORM\DataObject;

class Organisation extends DataObject
{
    private static $table_name = 'GHOrganisation';

    private static $db = [
        'Name' => 'Varchar(255)',
        'GithubId' => 'Int',
    ];

    private static $has_many = [
        'Repositories' => Repository::class,
    ];


}
