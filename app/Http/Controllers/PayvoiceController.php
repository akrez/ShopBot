<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use App\Services\PayvoiceService;
use Jenssegers\Agent\Agent;

class PayvoiceController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected PayvoiceService $payvoiceService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        return view('payvoice.index', [
            'payvoices' => $this->payvoiceService->getLatestBlogPayvoicesQuery($blog)->paginate(365),
        ]);
    }
}
