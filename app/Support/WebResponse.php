<?php

namespace App\Support;

use Illuminate\Contracts\Support\Responsable;

class WebResponse implements Responsable
{
    public function __construct(
        private ResponseBuilder $responseBuilder,
        private ?string $successfulRedirectTo = null
    ) {}

    public function toResponse($request)
    {
        if (! $this->responseBuilder->isSuccessful()) {
            return back()
                ->with('swal-error', $this->responseBuilder->getMessage())
                ->withInput($request->input())
                ->withErrors($this->responseBuilder->getErrors());
        }

        if ($this->successfulRedirectTo === null) {
            return back()
                ->with('swal-success', $this->responseBuilder->getMessage());
        }

        return redirect()
            ->to($this->successfulRedirectTo)
            ->with('swal-success', $this->responseBuilder->getMessage());
    }
}
