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

use Qsomazzi\Particle\Action\IndexAction;

class Index extends Operation implements OperationInterface
{
    public const TYPE = 'index';

    public static function build(
        ?string $name = null,
        ?string $path = null,
        string $controller = IndexAction::class,
        array $methods = ['GET', 'POST'],
    ): self {
        return new Index($name, $path, $controller, $methods);
    }

    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self
    {
        return self::build(
            sprintf('admin_%s_%s_index', strtolower($domain), $shortName),
            sprintf('%s/%s/%s', $prefix, strtolower($domain), $shortName),
        );
    }
}
