<?php

namespace MaximeRainville\GithubAudit\Models\GitHub;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use Symfony\Component\VarDumper\Cloner\Data;

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

    private static $summary_fields = [
        'Name',
        'Repositories.Count' => '# Repositories',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        if ($this->isInDB()) {
            $fields->addFieldsToTab(
                'Root.Users',
                [
                    GridField::create(
                        'Users',
                        'Users',
                        $this->Users,
                        GridFieldConfig_RecordViewer::create()
                    ),
                ]
            );
        }

        return $fields;
    }

    public function getUsers(): DataList
    {
        return User::get()->filter('Repositories.OrganisationID', $this->ID);
    }

}
