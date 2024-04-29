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

namespace Qsomazzi\Particle\Component;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('notifications', template: '@Particle/components/notifications.html.twig')]
final class Notifications
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function notifications(): array
    {
        $notifications = $this->requestStack->getSession()->get('notifications', []);

        return \is_array($notifications) ? $notifications : [];
    }
}
