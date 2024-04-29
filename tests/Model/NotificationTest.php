<?php

use Qsomazzi\Particle\Model\Notification;

test('Model notification', function () {
    $notification = new Notification('Foo');
    
    expect($notification)->toBeInstanceOf(Notification::class);
});
