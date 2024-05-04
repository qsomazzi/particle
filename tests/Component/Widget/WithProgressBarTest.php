<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Widget\WithProgressBar;

it('can be instantiated with a progress value', function () {
    $withProgressBar           = new WithProgressBar();
    $withProgressBar->title    = 'Task Completion';
    $withProgressBar->progress = 75;

    expect($withProgressBar)->toBeInstanceOf(WithProgressBar::class);
    expect($withProgressBar->title)->toEqual('Task Completion');
    expect($withProgressBar->progress)->toBeInt();
    expect($withProgressBar->progress)->toEqual(75);
});

it('inherits properties and methods from Widget', function () {
    $withProgressBar           = new WithProgressBar();
    $withProgressBar->title    = 'Task Completion';
    $withProgressBar->trending = 1;

    expect($withProgressBar->getTrendingIcon())->toEqual('trending-up');
    expect($withProgressBar->getTrendingColor())->toEqual('green');
});

it('has a default progress value of 0', function () {
    $withProgressBar = new WithProgressBar();

    expect($withProgressBar->progress)->toBeInt();
    expect($withProgressBar->progress)->toEqual(0);
});
