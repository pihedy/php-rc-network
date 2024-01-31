<?php declare(strict_types=1);

namespace RcNetwork\Component\Console\Input;

abstract class AbstractInput implements InputInterface
{
    protected array $data = [];

    protected array $options = [];

    protected array $arguments = [];

    abstract protected function parse(): void;

    public function getArgument(string $name): ?string
    {
        if (!$this->hasArgument($name)) {
            return null;
        }

        return $this->arguments[$name];
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getOption(string $name)
    {
        if (!$this->hasOption($name)) {
            return null;
        }

        return $this->options[$name];
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasArgument(string $name): bool
    {
        return isset($this->arguments[$name]);
    }

    public function hasOption(string $name): bool
    {
        return isset($this->options[$name]);
    }
}
