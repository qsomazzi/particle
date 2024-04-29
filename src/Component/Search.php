<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('search', template: '@Qsomazzi/components/search.html.twig')]
final class Search
{
}