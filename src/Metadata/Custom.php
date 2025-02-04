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

class Custom extends Operation implements OperationInterface
{
    public const TYPE = 'custom';

    public static function build(
        ?string $name = null,
        ?string $path = null,
        ?string $controller = null,
        array $methods = ['GET'],
        string $text = '',
        string $icon = '',
        ?bool $displayInRowActions = null,
        ?bool $displayInPageActions = null,
    ): self {
        return new Custom($name, $path, $controller, $methods, $text, $icon, $displayInRowActions, $displayInPageActions);
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
        $path = sprintf('%s/%s/%s%s', $prefix, strtolower($domain), $shortName, $config['path'] ?? sprintf('/%s', $shortName));

        return self::build(
            sprintf('admin_%s_%s_%s', strtolower($domain), $shortName, $config['name'] ?? 'custom'),
            $path,
            $config['controller'],
            $config['methods'] ?? ['GET'],
            $config['text'] ?? ucfirst($config['name'] ?? 'custom'),
            $config['icon'] ?? '',
            (bool)($config['displayInRowActions'] ?? str_contains($path, '{')),
            (bool)($config['displayInPageActions'] ?? true),
        );
    }
}
