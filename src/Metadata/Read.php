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

use Qsomazzi\Particle\Action\ReadAction;

class Read extends Operation implements OperationInterface
{
    public const TYPE = 'read';

    public static function build(
        ?string $name = null,
        ?string $path = null,
        string $controller = ReadAction::class,
        array $methods = ['GET'],
    ): self {
        return new Read($name, $path, $controller, $methods);
    }

    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self
    {
        return self::build(
            sprintf('admin_%s_%s_read', strtolower($domain), $shortName),
            sprintf('%s/%s/%s/{%s}', $prefix, strtolower($domain), $shortName, $identifier),
        );
    }
}
