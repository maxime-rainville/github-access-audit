<?php

namespace MaximeRainville\GithubAudit\Models\Packagist;

use MaximeRainville\GithubAudit\Models\Packagist\Package;
use SilverStripe\ORM\DataObject;

class PackageMaintainer extends DataObject
{
    private static $table_name = 'PackagistPackageMaintainer';

    private static $db = [
        'Roles' => 'Varchar(255)'
    ];

    private static $has_one = [
        'Package' => Package::class,
        'Maintainer' => Maintainer::class,
    ];
}
