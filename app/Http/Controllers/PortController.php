<?php

namespace App\Http\Controllers;

use App\Enums\Excel\SheetsName;
use App\Services\BlogService;
use App\Services\ContactService;
use App\Services\ProductPropertyService;
use App\Services\ProductService;
use App\Services\ProductTagService;
use App\Support\Excel;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function __construct(
        protected Excel $excel,
        protected BlogService $blogService,
        protected ProductService $productService,
        protected ProductTagService $productTagService,
        protected ProductPropertyService $productPropertyService,
        protected ContactService $contactService
    ) {}

    public function index(Request $request)
    {
        return view('port.index');
    }

    public function export(Request $request)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $fileName = date('Y-m-d-H-i-s').'.xlsx';

        return $this->excel->export($fileName, [
            SheetsName::PRODUCT->value => $this->productService->export($blog),
            SheetsName::PRODUCT_TAG->value => $this->productTagService->exportToExcel($blog),
            SheetsName::PRODUCT_PROPERTY->value => $this->productPropertyService->exportToExcel($blog),
            SheetsName::CONTACT->value => $this->contactService->exportToExcel($blog),
        ]);
    }

    public function import(Request $request)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $port = $request->file('port');

        if ($port and $path = $port->getRealPath()) {

            $source = $this->excel->read($path);

            $source += [
                SheetsName::PRODUCT->value => [],
                SheetsName::PRODUCT_TAG->value => [],
                SheetsName::PRODUCT_PROPERTY->value => [],
                SheetsName::CONTACT->value => [],
            ];

            $this->productService->import($blog, $source[SheetsName::PRODUCT->value]);
            $this->productTagService->importFromExcel($blog, $source[SheetsName::PRODUCT_TAG->value]);
            $this->productPropertyService->importFromExcel($blog, $source[SheetsName::PRODUCT_PROPERTY->value]);
            $this->contactService->importFromExcel($blog, $source[SheetsName::CONTACT->value]);
        }

        return back();
    }
}
