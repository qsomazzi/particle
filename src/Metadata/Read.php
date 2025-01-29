<?php

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