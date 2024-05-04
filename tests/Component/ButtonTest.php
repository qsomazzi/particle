<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Button;

it('has default type', function () {
    $button = new Button();

    expect($button->type)->toEqual('default');
});

it('can change type', function () {
    $button = new Button();

    $button->type = 'primary';

    expect($button->type)->toEqual('primary');
});

it('returns correct type classes', function () {
    $button = new Button();

    expect($button->getTypeClasses())->toEqual('');

    $button->type = 'primary';
    expect($button->getTypeClasses())->toEqual('btn-primary');

    $button->type = 'success';
    expect($button->getTypeClasses())->toEqual('btn-success');

    $button->type = 'warning';
    expect($button->getTypeClasses())->toEqual('btn-warning');

    $button->type = 'danger';
    expect($button->getTypeClasses())->toEqual('btn-danger');
});

it('throws an exception for unknown types', function () {
    $button       = new Button();
    $button->type = 'unknown';

    $this->expectException(LogicException::class);
    $this->expectExceptionMessage('Unknown button type "unknown"');

    $button->getTypeClasses();
});
