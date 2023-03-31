<?php

namespace MaximeRainville\GithubAudit\Tasks;

use MaximeRainville\GithubAudit\Models\Repository;
use MaximeRainville\GithubAudit\Models\RepoUser;
use MaximeRainville\GithubAudit\Models\User;

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
