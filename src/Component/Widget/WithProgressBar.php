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

namespace Qsomazzi\Particle\Component\Widget;

use Qsomazzi\Particle\Component\Widget;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('widget:withProgressBar', template: '@Particle/components/widget/withProgressBar.html.twig')]
final class WithProgressBar extends Widget
{
    public int $progress = 0;
}
