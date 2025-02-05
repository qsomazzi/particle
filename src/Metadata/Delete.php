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

use Qsomazzi\Particle\Action\DeleteAction;

class Delete extends Operation implements OperationInterface
{
    public const TYPE = 'delete';

    public static function build(
        ?string $name = null,
        ?string $path = null,
        string $controller = DeleteAction::class,
        array $methods = ['GET', 'POST'],
        string $text = 'Delete',
        string $icon = 'ti ti-trash',
    ): self {
        return new Delete($name, $path, $controller, $methods, $text, $icon, null, null);
    }

    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self
    {
        return self::build(
            sprintf('admin_%s_%s_delete', strtolower($domain), $shortName),
            sprintf('%s/%s/%s/{%s}/delete', $prefix, strtolower($domain), $shortName, $identifier),
        );
    }
}
