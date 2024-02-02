<?php declare(strict_types=1);

namespace RcNetwork\Component\Config;

use \Symfony\Component\Config\Loader\FileLoader;

class PhpFileLoader extends FileLoader
{
    public function load(mixed $resource, string $type = null): mixed
    {
        return require $this->locator->locate($resource);
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
