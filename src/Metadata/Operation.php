<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qsomazzi\Particle\Metadata;

abstract class Operation
{
    public function __construct(
        private ?string $name,
        private ?string $path,
        private string $controller,
        private array $methods,
        private string $text,
        private string $icon,
        private ?bool $displayInRowActions,
        private ?bool $displayInPageActions,
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

    public function getText(): string
    {
        return $this->text;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function isDisplayInRowActions(): bool
    {
        return $this->displayInRowActions ?? false;
    }

    public function isDisplayInPageActions(): bool
    {
        return $this->displayInPageActions ?? false;
    }

    public function toArray(): array
    {
        return [
            'name'                 => $this->name,
            'path'                 => $this->path,
            'controller'           => $this->controller,
            'methods'              => $this->methods,
            'text'                 => $this->text,
            'icon'                 => $this->icon,
            'displayInRowActions'  => $this->displayInRowActions,
            'displayInPageActions' => $this->displayInPageActions,
            'type'                 => static::TYPE,
        ];
    }
}
