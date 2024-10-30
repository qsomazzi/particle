<?php

declare(strict_types=1);

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qsomazzi\Particle\Model;

final class Notification
{
    public function __construct(
        public string $title,
        public string $description = '',
        public string $link = '',
        public string $color = '',
    ) {
    }
}
