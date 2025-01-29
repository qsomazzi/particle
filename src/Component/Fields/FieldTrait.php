<?php

declare(strict_types=1);

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qsomazzi\Particle\Component\Fields;

use Qsomazzi\Particle\Metadata\Index;

trait FieldTrait
{
    private ?string $key = null;
    private ?string $label = null;
    private mixed $value = null;
    private bool $sortable = true;
    private string $display = Index::TYPE;

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        if (\is_null($label)) {
            // Create a new label from key, add a space before each capital letter
            $label = ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $this->key));
        }

        $this->label = $label;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setSortable(bool $sortable): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }
}
