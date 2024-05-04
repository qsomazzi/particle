<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Widget\WithBarChart;

it('can be instantiated with labels and data', function () {
    $withBarChart         = new WithBarChart();
    $withBarChart->title  = 'Monthly Revenue';
    $withBarChart->value  = '$5000';
    $withBarChart->labels = ['April', 'May', 'June'];
    $withBarChart->data   = [3000, 4000, 3500];

    expect($withBarChart)->toBeInstanceOf(WithBarChart::class);
    expect($withBarChart->title)->toEqual('Monthly Revenue');
    expect($withBarChart->value)->toEqual('$5000');
    expect($withBarChart->labels)->toBeArray();
    expect($withBarChart->labels)->toMatchArray(['April', 'May', 'June']);
    expect($withBarChart->data)->toBeArray();
    expect($withBarChart->data)->toMatchArray([3000, 4000, 3500]);
});

it('inherits properties and methods from Widget', function () {
    $withBarChart           = new WithBarChart();
    $withBarChart->title    = 'Monthly Revenue';
    $withBarChart->trending = -1;

    expect($withBarChart->getTrendingIcon())->toEqual('trending-down');
    expect($withBarChart->getTrendingColor())->toEqual('red');
});

it('has empty arrays for labels and data by default', function () {
    $withBarChart = new WithBarChart();

    expect($withBarChart->labels)->toBeArray();
    expect($withBarChart->labels)->toBeEmpty();
    expect($withBarChart->data)->toBeArray();
    expect($withBarChart->data)->toBeEmpty();
});
