<?php

namespace Qsomazzi\Particle\RequestPayload;

class IndexPayload
{
    public function __construct(
        public readonly int     $page = 1,
        public readonly ?string $sort = null,
        public readonly ?string $sortDirection = null,
        public readonly ?string $query = null,
        public ?array  $filters = [],
    ) {
    }

    public function all(): array
    {
        $params = [];

        if ($this->page !== 1) {
            $params['page'] = $this->page;
        }
        if ($this->sort !== null) {
            $params['sort'] = $this->sort;
        }
        if ($this->sortDirection !== null) {
            $params['sortDirection'] = $this->sortDirection;
        }
        if ($this->query !== null) {
            $params['query'] = $this->query;
        }

        return $params;
    }
}
