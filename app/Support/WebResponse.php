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
        if (
            $this->successfulRedirectTo === null or
            ! $this->responseBuilder->isSuccessful()
        ) {
            return back()
                ->with('message', $this->responseBuilder->getMessage())
                ->withInput($request->input())
                ->withErrors($this->responseBuilder->getErrors());
        }

        return redirect()
            ->to($this->successfulRedirectTo)
            ->with('swal-success', $this->responseBuilder->getMessage());
    }
}
