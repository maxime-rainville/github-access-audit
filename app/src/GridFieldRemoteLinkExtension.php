<?php

namespace MaximeRainville\GithubAudit;

use SilverStripe\Core\Extension;

class GridFieldRemoteLinkExtension extends Extension
{
    public function updateConfig()
    {
        $this->getOwner()->addComponent(GridFieldRemoteLink::create());
    }
}
