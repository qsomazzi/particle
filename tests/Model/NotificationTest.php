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

it('can be created with initial values', function () {
    $notification = new Notification('Foo');

    expect($notification)->toBeInstanceOf(Notification::class);
    expect($notification->title)->toEqual('Foo');
    expect($notification->description)->toEqual('');
    expect($notification->link)->toEqual('');
    expect($notification->color)->toEqual('');
});

it('can be created with more values', function () {
    $notification = new Notification('Foo', 'Bar', '#', 'red');

    expect($notification)->toBeInstanceOf(Notification::class);
    expect($notification->title)->toEqual('Foo');
    expect($notification->description)->toEqual('Bar');
    expect($notification->link)->toEqual('#');
    expect($notification->color)->toEqual('red');
});
