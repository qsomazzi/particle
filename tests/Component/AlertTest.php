<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Alert;

it('has default properties', function () {
    $alert = new Alert();

    expect($alert->type)->toEqual('success');
    expect($alert->icon)->toBeNull();
    expect($alert->description)->toBeNull();
    expect($alert->dismissible)->toBeTrue();
    expect($alert->important)->toBeFalse();
});

it('can change properties', function () {
    $alert = new Alert();

    $alert->type        = 'danger';
    $alert->icon        = 'warning-icon';
    $alert->description = 'An error occurred';
    $alert->dismissible = false;
    $alert->important   = true;

    expect($alert->type)->toEqual('danger');
    expect($alert->icon)->toEqual('warning-icon');
    expect($alert->description)->toEqual('An error occurred');
    expect($alert->dismissible)->toBeFalse();
    expect($alert->important)->toBeTrue();
});

it('returns correct type classes', function () {
    $alert       = new Alert();
    $alert->type = 'success';

    expect($alert->getTypeClasses())->toContain('alert alert-success');
    expect($alert->getTypeClasses())->toContain('alert-dismissible');
    expect($alert->getTypeClasses())->not->toContain('alert-important');

    $alert->dismissible = false;
    expect($alert->getTypeClasses())->not->toContain('alert-dismissible');

    $alert->important = true;
    expect($alert->getTypeClasses())->toContain('alert-important');
});

it('throws an exception for unknown types', function () {
    $alert       = new Alert();
    $alert->type = 'unknown';

    $this->expectException(LogicException::class);
    $this->expectExceptionMessage('Unknown alert type "unknown"');

    $alert->getTypeClasses();
});
