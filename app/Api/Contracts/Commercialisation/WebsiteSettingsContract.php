<?php

namespace Laraspace\Api\Contracts\Commercialisation;

interface WebsiteSettingsContract
{
    /*
     * Buy license 
     *
     * @param void
     * @return view
     */

    public function saveSettings($data);
    public function getSettings($data);
}

?>