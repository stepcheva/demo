<?php


namespace App\Http\Controllers\Api\v1;

use Illuminate\Pagination\LengthAwarePaginator;

class TapePaginator extends LengthAwarePaginator
{
    public function nextPage()
    {
        if ($this->lastPage() > $this->currentPage()) {
            return $this->currentPage() + 1;
        }
    }

    public function toArray()
    {
        return [
            'tape' => $this->items->toArray(),
            'current_page' => $this->currentPage(),
            'next_page' => $this->nextPage(),
            'per_page' => $this->perPage(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'last_page' => $this->lastPage(),
            'total' => $this->total(),
        ];
    }
}