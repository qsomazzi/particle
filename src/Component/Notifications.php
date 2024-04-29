<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('notifications', template: '@Qsomazzi/Particle/components/notifications.html.twig')]
final class Notifications
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function notifications(): array
    {
        return $this->requestStack->getSession()->get('notifications', []);
    }
}
