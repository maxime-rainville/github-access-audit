<?php

namespace MaximeRainville\GithubAudit\Models\Packagist;

use SilverStripe\ORM\DataObject;

class Organisation extends DataObject
{
    private static $table_name = 'PackagistOrganisation';

    private static $db = [
        'Title' => 'Varchar(255)',
    ];

    private static $has_many = [
        'Packages' => Package::class,
    ];

    private static $summary_fields = [
        'Title',
        'Packages.Count' => '# Packages',
    ];
}
