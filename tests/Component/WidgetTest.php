<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Widget;

it('can be instantiated with title and value', function () {
    $widget        = new Widget();
    $widget->title = 'Total Sales';
    $widget->value = '$1000';

    expect($widget)->toBeInstanceOf(Widget::class);
    expect($widget->title)->toEqual('Total Sales');
    expect($widget->value)->toEqual('$1000');
});

it('has optional trending properties', function () {
    $widget = new Widget();

    expect($widget->trending)->toBeNull();
    expect($widget->trendingUnit)->toEqual('');
});

it('returns correct trending icon', function () {
    $widget = new Widget();

    $widget->trending = 1;
    expect($widget->getTrendingIcon())->toEqual('trending-up');

    $widget->trending = -1;
    expect($widget->getTrendingIcon())->toEqual('trending-down');

    $widget->trending = 0;
    expect($widget->getTrendingIcon())->toEqual('minus');

    $widget->trending = null;
    expect($widget->getTrendingIcon())->toEqual('minus');
});

it('returns correct trending color', function () {
    $widget = new Widget();

    $widget->trending = 1;
    expect($widget->getTrendingColor())->toEqual('green');

    $widget->trending = -1;
    expect($widget->getTrendingColor())->toEqual('red');

    $widget->trending = 0;
    expect($widget->getTrendingColor())->toEqual('orange');

    $widget->trending = null;
    expect($widget->getTrendingColor())->toEqual('orange');
});

it('generates a unique block id', function () {
    $widget   = new Widget();
    $blockId1 = $widget->getBlockId();
    $blockId2 = $widget->getBlockId();

    expect($blockId1)->toBeString();
    expect($blockId2)->toBeString();
    expect($blockId1)->not()->toEqual($blockId2);
});
