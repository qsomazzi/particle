<?php

namespace Qsomazzi\Particle\RequestPayload;

class IndexPayload
{
    public function __construct(
        public int     $page = 1,
        public ?string $sort = null,
        public ?string $sortDirection = null,
        public ?string $query = null,
        public ?array  $filters = null,
    ) {
    }

    public function buildFilters():void
    {
        unset($this->filters['submit']);
        unset($this->filters['_token']);

        if (isset($this->filters['clear'])) {
            $this->filters = [];
        }

        foreach ($this->filters as $filter => $value) {
            if ($value === '') {
                unset($this->filters[$filter]);
            }
        }
    }
}
