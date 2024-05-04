<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Model\AccordionItem;

it('can be created with initial values', function () {
    $accordionItem = new AccordionItem('Test Title', 'Test Content');

    expect($accordionItem)->toBeInstanceOf(AccordionItem::class);
    expect($accordionItem->title)->toEqual('Test Title');
    expect($accordionItem->content)->toEqual('Test Content');
});
