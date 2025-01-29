<?php

namespace Qsomazzi\Particle\Metadata;

use Qsomazzi\Particle\Action\DeleteAction;

class Delete extends Operation implements OperationInterface
{
    public const TYPE = 'delete';

    public static function build(
        ?string $name = null,
        ?string $path = null,
        string $controller = DeleteAction::class,
        array $methods = ['POST'],
    ): self {
        return new Delete($name, $path, $controller, $methods);
    }

    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self
    {
        return self::build(
            sprintf('admin_%s_%s_delete', strtolower($domain), $shortName),
            sprintf('%s/%s/%s/{%s}', $prefix, strtolower($domain), $shortName, $identifier),
        );
    }
}
