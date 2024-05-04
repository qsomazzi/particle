<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Accordion;

it('can add and retrieve items', function () {
    $accordion = new Accordion();
    $items     = [
        ['title' => 'Item 1', 'content' => 'Content 1'],
        ['title' => 'Item 2', 'content' => 'Content 2'],
    ];

    $accordion->items = $items;

    expect($accordion->getItems())->toBeArray();
    expect($accordion->getItems())->toHaveCount(2);
    expect($accordion->getItems())->toEqual($items);
});

it('generates a unique block id', function () {
    $accordion = new Accordion();
    $blockId1  = $accordion->getBlockId();
    $blockId2  = $accordion->getBlockId();

    expect($blockId1)->toBeString();
    expect($blockId2)->toBeString();
    expect($blockId1)->not()->toEqual($blockId2);
});

it('has an empty items array by default', function () {
    $accordion = new Accordion();

    expect($accordion->getItems())->toBeArray();
    expect($accordion->getItems())->toBeEmpty();
});
