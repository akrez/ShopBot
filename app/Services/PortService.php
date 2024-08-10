<?php

namespace App\Services;

use App\Contracts\PortContract;
use App\Enums\Excel\SheetName;
use App\Models\Blog;
use Illuminate\Support\Arr;

class PortService
{
    public function getRelativeService(SheetName $sheetName): PortContract
    {
        $serviceClassName = Arr::get([
            SheetName::PRODUCT->value => ProductService::class,
            SheetName::PRODUCT_TAG->value => ProductTagService::class,
            SheetName::PRODUCT_PROPERTY->value => ProductPropertyService::class,
            SheetName::CONTACT->value => ContactService::class,
        ], $sheetName->value);

        abort_unless($serviceClassName, 500);

        return resolve($serviceClassName);
    }

    public function exportToExcel(SheetName $sheetName, Blog $blog)
    {
        $service = $this->getRelativeService($sheetName);

        return $this->exportToExcelByService($service, $blog);
    }

    public function importFromExcel(SheetName $sheetName, Blog $blog, array $rows)
    {
        $service = $this->getRelativeService($sheetName);

        return $this->importFromExcelByService($service, $blog, $rows);
    }

    private function exportToExcelByService(PortContract $service, Blog $blog)
    {
        return $service->exportToExcel($blog);
    }

    private function importFromExcelByService(PortContract $service, Blog $blog, array $rows)
    {
        return $service->importFromExcel($blog, $rows);
    }
}
