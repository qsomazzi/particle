<?php

namespace Qsomazzi\Particle\Metadata;

abstract class Operation
{
    public function __construct(
        private readonly ?string $name,
        private readonly ?string $path,
        private readonly string  $controller,
        private readonly array   $methods,
    ) {
    }

    abstract public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function toArray(): array
    {
        return [
            'name'       => $this->name,
            'path'       => $this->path,
            'controller' => $this->controller,
            'methods'    => $this->methods,
            'type'       => static::TYPE,
        ];
    }
}