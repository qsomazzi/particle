<?php

declare(strict_types=1);

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
