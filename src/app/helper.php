<?php declare(strict_types=1);

if (!function_exists('RcNetworkApp')) {
    /**
     * Get the RcNetwork\App singleton instance.
     *
     * @return \RcNetwork\App
     */
    function RcNetworkApp(): \RcNetwork\App
    {
        return \RcNetwork\App::getInstance();
    }
}
