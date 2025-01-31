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

interface OperationInterface
{
    public static function build(?string $name, ?string $path, string $controller, array $methods): self;

    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self;
}
