<?php

namespace Qsomazzi\Particle\Metadata;

interface OperationInterface
{
    public static function build(?string $name, ?string $path, string $controller, array $methods): self;
    public static function buildFromGuesser(string $prefix, string $domain, string $shortName, string $identifier): self;
}