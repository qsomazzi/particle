<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Widget\WithAreaChart;

it('can be instantiated with labels and data', function () {
    $withAreaChart         = new WithAreaChart();
    $withAreaChart->title  = 'Sales Over Time';
    $withAreaChart->value  = '$1000';
    $withAreaChart->labels = ['January', 'February', 'March'];
    $withAreaChart->data   = [150, 200, 250];

    expect($withAreaChart)->toBeInstanceOf(WithAreaChart::class);
    expect($withAreaChart->title)->toEqual('Sales Over Time');
    expect($withAreaChart->value)->toEqual('$1000');
    expect($withAreaChart->labels)->toBeArray();
    expect($withAreaChart->labels)->toMatchArray(['January', 'February', 'March']);
    expect($withAreaChart->data)->toBeArray();
    expect($withAreaChart->data)->toMatchArray([150, 200, 250]);
});

it('inherits properties and methods from Widget', function () {
    $withAreaChart           = new WithAreaChart();
    $withAreaChart->title    = 'Sales Over Time';
    $withAreaChart->trending = 1;

    expect($withAreaChart->getTrendingIcon())->toEqual('trending-up');
    expect($withAreaChart->getTrendingColor())->toEqual('green');
});

it('has empty arrays for labels and data by default', function () {
    $withAreaChart = new WithAreaChart();

    expect($withAreaChart->labels)->toBeArray();
    expect($withAreaChart->labels)->toBeEmpty();
    expect($withAreaChart->data)->toBeArray();
    expect($withAreaChart->data)->toBeEmpty();
});
