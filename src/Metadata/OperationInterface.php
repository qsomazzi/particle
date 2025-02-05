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
    public const LOCATION_ACTIONS = 'actions';
    public const LOCATION_PAGE    = 'page';

    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self;
}
