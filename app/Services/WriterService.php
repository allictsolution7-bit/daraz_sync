<?php

namespace App\Services;

use App\Models\Writer;

class WriterService
{
    public function getBestWriters()
    {
        // Best writer can be calculated and sorted by different parameters
        // 1. Number of books sold
        // 2. Reviews
        // 3. Number of books published by Him/Her
        // 4. Alphabetical order

        return Writer::orderByDesc('popularity_score')->select('id', 'name', 'photo')->paginate();
    }
}
