<?php

namespace App\Http\Controllers;

use App\Enums\Excel\SheetName;
use App\Services\BlogService;
use App\Services\PortService;
use App\Support\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PortController extends Controller
{
    public function __construct(
        protected PortService $portService,
        protected BlogService $blogService,
        protected Excel $excel
    ) {}

    public function getRelativeView(SheetName $sheetName)
    {
        $view = Arr::get([
            SheetName::PRODUCT->value => 'ports._product',
            SheetName::PRODUCT_TAG->value => 'ports._product_tag',
            SheetName::PRODUCT_PROPERTY->value => 'ports._product_property',
            SheetName::CONTACT->value => 'ports._contact',
        ], $sheetName->value);

        abort_unless($view, 500);

        return $view;
    }

    public function index(Request $request, SheetName $sheetName)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $responseBuilders = [];
        if (
            $port = $request->file('port') and
            $path = $port->getRealPath()
        ) {
            $source = $this->excel->read($path);
            $responseBuilders = $this->portService->importFromExcel($sheetName, $blog, Arr::get($source, $sheetName->value, []));
        }

        return view('ports.index', [
            'sheetName' => $sheetName,
            'responseBuilders' => $responseBuilders,
            'responseBuilderView' => $this->getRelativeView($sheetName),
        ]);
    }

    public function export(Request $request, SheetName $sheetName)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $fileName = date('Y-m-d-H-i-s').'_'.$sheetName->value.'.xlsx';

        return $this->excel->export($fileName, [
            $sheetName->value => $this->portService->exportToExcel($sheetName, $blog),
        ]);
    }
}
