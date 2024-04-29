<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component;

use Qsomazzi\Particle\Model\Notification;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('notificationsItem', template: '@Qsomazzi/components/notificationsItem.html.twig')]
final class NotificationsItem
{
    public Notification $notification;
}
