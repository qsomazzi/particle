<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Model\Notification;

test('Model notification', function () {
    $notification = new Notification('Foo');

    expect($notification)->toBeInstanceOf(Notification::class);
});
