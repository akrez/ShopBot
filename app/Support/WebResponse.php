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
        if ($this->responseBuilder->isSuccessful()) {
            return redirect()
                ->to($this->successfulRedirectTo)
                ->with('message', $this->responseBuilder->getMessage());
        } else {
            return back()
                ->with('message', $this->responseBuilder->getMessage())
                ->withErrors($this->responseBuilder->getErrors())
                ->withInput($request->input());
        }
    }
}
