<?php
namespace MaximeRainville\GithubAudit\Admin;

use MaximeRainville\GithubAudit\Models\Packagist\Maintainer;
use MaximeRainville\GithubAudit\Models\Packagist\Organisation;
use MaximeRainville\GithubAudit\Models\Packagist\Package;
use SilverStripe\Admin\ModelAdmin;

class Packagist extends ModelAdmin
{

    private static $url_segment = 'packagist';

    private static $menu_title = 'Packagist';

    private static $managed_models = [
        'organisation' => [
            'title' => 'Organisations',
            'dataClass' => Organisation::class
        ],
        'package' => [
            'title' => 'Packages',
            'dataClass' => Package::class
        ],
        'maintainer' => [
            'title' => 'Maintainers',
            'dataClass' => Maintainer::class
        ],
    ];

}

