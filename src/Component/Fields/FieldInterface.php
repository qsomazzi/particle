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

interface FieldInterface
{
    public static function new(string $key, ?string $label = null);

    public function isSortable(): bool;
}
