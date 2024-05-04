<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Qsomazzi\Particle\Component\Search;

it('can be instantiated', function () {
    $search = new Search();

    expect($search)->toBeInstanceOf(Search::class);
});
