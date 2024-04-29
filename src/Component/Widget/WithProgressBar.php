<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component\Widget;

use Qsomazzi\Particle\Component\Widget;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('widget:withProgressBar', template: '@Qsomazzi/Particle/components/widget/withProgressBar.html.twig')]
final class WithProgressBar extends Widget
{
    public int $progress = 0;
}
