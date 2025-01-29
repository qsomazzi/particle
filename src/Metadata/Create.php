<?php

namespace Qsomazzi\Particle\Metadata;

use Qsomazzi\Particle\Action\CreateAction;

class Create extends Operation implements OperationInterface
{
    public const TYPE = 'create';

    public static function build(
        ?string $name = null,
        ?string $path = null,
        string $controller = CreateAction::class,
        array $methods = ['GET', 'POST'],
    ): self {
        return new Create($name, $path, $controller, $methods);
    }

    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self
    {
        return self::build(
            sprintf('admin_%s_%s_create', strtolower($domain), $shortName),
            sprintf('%s/%s/%s/new', $prefix, strtolower($domain), $shortName),
        );
    }
}