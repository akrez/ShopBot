<?php

namespace App\Contracts;

use App\Models\Blog;

interface PortContract
{
    public function importFromExcel(Blog $blog, array $rows);

    public function exportToExcel(Blog $blog);
}
