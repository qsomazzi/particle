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

use Qsomazzi\Particle\Model\Notification;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('notificationsItem', template: '@Particle/components/notificationsItem.html.twig')]
final class NotificationsItem
{
    public Notification $notification;
}
