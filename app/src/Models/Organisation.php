<?php

namespace MaximeRainville\GithubAudit\Models;

use SilverStripe\ORM\DataObject;

class Organisation extends DataObject
{
    private static $table_name = 'Organisation';

    private static $db = [
        'Name' => 'Varchar(255)',
        'GithubId' => 'Int',
    ];

    private static $has_many = [
        'Repositories' => Repository::class,
    ];


}
