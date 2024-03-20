<?php

namespace MaximeRainville\GithubAudit;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_ActionMenuItem;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldStateAware;
use SilverStripe\Forms\GridField\GridFieldViewButton;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

class GridFieldRemoteLink extends GridFieldViewButton
{
    /**
     * @inheritdoc
     */
    public function getTitle($gridField, $record, $columnName)
    {
        return 'View online';
    }

    /**
     * @inheritdoc
     */
    public function getGroup($gridField, $record, $columnName)
    {
        if (!$record->hasMethod('getRemoteLink')) {
            return '';
        }
        return GridField_ActionMenuItem::DEFAULT_GROUP;
    }

    /**
     * @inheritdoc
     */
    public function getExtraData($gridField, $record, $columnName)
    {
        return [
            "classNames" => "font-icon-link action-detail"
        ];
    }

    /**
     * @inheritdoc
     * @param bool $addState DEPRECATED: Should be removed in major release
     */
    public function getUrl($gridField, $record, $columnName, $addState = true)
    {
        if (!$record->hasMethod('getRemoteLink')) {
            return '';
        }

        $link = $record->getRemoteLink();

        return $gridField->addAllStateToUrl($link, $addState);
    }

    /**
     * @param GridField $gridField
     * @param DataObject $record
     * @param string $columnName
     * @return string The HTML for the column
     */
    public function getColumnContent($gridField, $record, $columnName)
    {
        if (!$record->hasMethod('getRemoteLink')) {
            return '';
        }
        // No permission checks, handled through GridFieldDetailForm,
        // which can make the form readonly if no edit permissions are available.
        $data = new ArrayData([
            'Link' => $this->getURL($gridField, $record, $columnName, false)
        ]);

        $template = SSViewer::get_templates_by_class($this, '', __CLASS__);
        return $data->renderWith($template);
    }

}
