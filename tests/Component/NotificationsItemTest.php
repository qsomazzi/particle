<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\NotificationsItem;
use Qsomazzi\Particle\Model\Notification;

it('holds a notification instance', function () {
    $notification                    = new Notification('success', 'Operation successful');
    $notificationsItem               = new NotificationsItem();
    $notificationsItem->notification = $notification;

    expect($notificationsItem->notification)->toBeInstanceOf(Notification::class);
});
