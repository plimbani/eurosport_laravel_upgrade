<?php

namespace App\Custom\Http;

use Illuminate\Http\Request as Base;

/**
 * Custom Request class for proper ssl detection
 *
 * @author Justin van Schaick <me@domain.nl>
 */
class Request extends Base
{
    /**
     * @return bool
     */
    public function isSecure()
    {
        $isSecure = parent::isSecure();

        if ($isSecure) {
            return true;
        }

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            return true;
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || ! empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            return true;
        }

        return false;
    }
}
