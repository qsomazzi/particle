<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Widget\WithLineChart;

it('can be instantiated with labels and data', function () {
    $withLineChart         = new WithLineChart();
    $withLineChart->title  = 'Website Traffic';
    $withLineChart->value  = '10K visitors';
    $withLineChart->labels = ['Week 1', 'Week 2', 'Week 3'];
    $withLineChart->data   = [500, 1000, 1500];

    expect($withLineChart)->toBeInstanceOf(WithLineChart::class);
    expect($withLineChart->title)->toEqual('Website Traffic');
    expect($withLineChart->value)->toEqual('10K visitors');
    expect($withLineChart->labels)->toBeArray();
    expect($withLineChart->labels)->toMatchArray(['Week 1', 'Week 2', 'Week 3']);
    expect($withLineChart->data)->toBeArray();
    expect($withLineChart->data)->toMatchArray([500, 1000, 1500]);
});

it('inherits properties and methods from Widget', function () {
    $withLineChart           = new WithLineChart();
    $withLineChart->title    = 'Website Traffic';
    $withLineChart->trending = 0;

    expect($withLineChart->getTrendingIcon())->toEqual('minus');
    expect($withLineChart->getTrendingColor())->toEqual('orange');
});

it('has empty arrays for labels and data by default', function () {
    $withLineChart = new WithLineChart();

    expect($withLineChart->labels)->toBeArray();
    expect($withLineChart->labels)->toBeEmpty();
    expect($withLineChart->data)->toBeArray();
    expect($withLineChart->data)->toBeEmpty();
});
