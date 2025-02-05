<?php

namespace Qsomazzi\Particle\RequestPayload;

class IndexPayload
{
    public function __construct(
        public int     $page = 1,
        public ?string $sort = null,
        public ?string $sortDirection = null,
        public ?string $query = null,
    ) {
    }
}
