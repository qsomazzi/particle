<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Notifications;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

beforeEach(function () {
    $session = new Session(new MockArraySessionStorage());
    $request = new Request();
    $request->setSession($session);

    $this->requestStack = new RequestStack();
    $this->requestStack->push($request);
    $this->notifications = new Notifications($this->requestStack);
});

it('retrieves empty notifications by default', function () {
    expect($this->notifications->notifications())->toBeArray();
    expect($this->notifications->notifications())->toBeEmpty();
});

it('retrieves stored notifications', function () {
    $session               = $this->requestStack->getSession();
    $expectedNotifications = [
        ['type' => 'success', 'message' => 'Operation successful'],
        ['type' => 'error', 'message' => 'An error occurred'],
    ];
    $session->set('notifications', $expectedNotifications);

    expect($this->notifications->notifications())->toBeArray();
    expect($this->notifications->notifications())->toMatchArray($expectedNotifications);
});

it('returns an empty array if notifications are not an array', function () {
    $session = $this->requestStack->getSession();
    $session->set('notifications', 'not an array');

    expect($this->notifications->notifications())->toBeArray();
    expect($this->notifications->notifications())->toBeEmpty();
});
