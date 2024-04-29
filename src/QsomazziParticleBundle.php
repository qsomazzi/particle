<?php

declare(strict_types=1);

namespace Qsomazzi\Particle;

use Qsomazzi\Particle\DependencyInjection\QsomazziParticleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class QsomazziParticleBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new QsomazziParticleExtension();
    }
}