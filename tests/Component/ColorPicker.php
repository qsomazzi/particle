<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\ColorPicker;

it('has default properties', function () {
    $colorPicker = new ColorPicker();

    expect($colorPicker->label)->toBeNull();
    expect($colorPicker->defaultColor)->toEqual('#0054a6');
    expect($colorPicker->swatches)->toBeArray();
    expect($colorPicker->swatches)->toMatchArray([
        '#0054a6',
        '#45aaf2',
        '#6574cd',
        '#a55eea',
        '#f66d9b',
        '#fa4654',
        '#fd9644',
        '#f1c40f',
        '#7bd235',
        '#5eba00',
        '#2bcbba',
        '#17a2b8',
    ]);
});

it('can change properties', function () {
    $colorPicker = new ColorPicker();

    $colorPicker->label        = 'Choose Color';
    $colorPicker->defaultColor = '#ff0000';
    $colorPicker->swatches     = ['#ffffff', '#000000'];

    expect($colorPicker->label)->toEqual('Choose Color');
    expect($colorPicker->defaultColor)->toEqual('#ff0000');
    expect($colorPicker->swatches)->toBeArray();
    expect($colorPicker->swatches)->toMatchArray(['#ffffff', '#000000']);
});

it('generates a unique block id', function () {
    $colorPicker = new ColorPicker();
    $blockId1    = $colorPicker->getBlockId();
    $blockId2    = $colorPicker->getBlockId();

    expect($blockId1)->toBeString();
    expect($blockId2)->toBeString();
    expect($blockId1)->not()->toEqual($blockId2);
});

it('has a predefined set of swatches', function () {
    $colorPicker = new ColorPicker();

    expect($colorPicker->swatches)->toBeArray();
    expect($colorPicker->swatches)->toHaveCount(12);
    expect($colorPicker->swatches[0])->toEqual('#0054a6');
});
