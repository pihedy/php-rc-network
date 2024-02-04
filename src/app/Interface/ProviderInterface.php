<?php declare(strict_types=1);

namespace RcNetwork\Interface;

use \RcNetwork\App;

/**
 * ProviderInterface defines the interface that providers must implement. 
 * Providers allow registering handlers and middleware to be used by the App.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 */
interface ProviderInterface
{
    /**
     * Registers handlers and middleware with the App instance.
     *
     * @param App $app The App instance to register with.
     */
    public function register(App $App): void;
}
