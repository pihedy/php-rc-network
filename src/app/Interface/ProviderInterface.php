<?php declare(strict_types=1);

namespace RcNetwork\Interface;

use \RcNetwork\App;

interface ProviderInterface
{
    public function register(App $App): void;
}
