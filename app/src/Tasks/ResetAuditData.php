<?php

namespace MaximeRainville\GithubAudit\Tasks;

use MaximeRainville\GithubAudit\Models\GitHub\Repository;
use MaximeRainville\GithubAudit\Models\GitHub\RepoUser;
use MaximeRainville\GithubAudit\Models\GitHub\User;
use MaximeRainville\GithubAudit\Models\Packagist\Maintainer;
use MaximeRainville\GithubAudit\Models\Packagist\Package;
use MaximeRainville\GithubAudit\Models\Packagist\PackageMaintainer;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObjectSchema;
use SilverStripe\ORM\DB;

class ResetAuditData extends BuildTask
{

    private static $segment = 'reset-audit-data';

    public function run($request)
    {
        $dataobjects = [
            User::class,
            Repository::class,
            RepoUser::class,
            Maintainer::class,
            Package::class,
            PackageMaintainer::class
        ];

        $schema = DataObjectSchema::singleton();
        $conx = DB::get_conn();

        foreach ($dataobjects as $do) {
            echo "Blasting $do\n";
            $table = $schema->tableName($do);
            $conx->clearTable($table);
        }

    }

}
