<?php

namespace Qsomazzi\Particle\Metadata;

class Custom extends Operation implements OperationInterface
{
    public const TYPE = 'custom';

    public static function build(
        ?string $name = null,
        ?string $path = null,
        ?string $controller = null,
        array $methods = ['GET'],
    ): self {
        return new Custom($name, $path, $controller, $methods);
    }

    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self
    {
        return self::build(
            sprintf('admin_%s_%s_custom', strtolower($domain), $shortName),
            sprintf('%s/%s/%s/custom', $prefix, strtolower($domain), $shortName),
        );
    }

    public static function buildFromConfig(array $config, string $prefix, string $domain, string $shortName, string $identifier): self
    {
        return self::build(
            sprintf('admin_%s_%s_%s', strtolower($domain), $shortName, $config['name'] ?? 'custom'),
            sprintf('%s/%s/%s%s', $prefix, strtolower($domain), $shortName, $config['path'] ?? '/custom'),
            $config['controller'],
            $config['methods'] ?? ['GET'],
        );
    }
}