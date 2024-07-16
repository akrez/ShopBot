<?php

namespace App\Supports;

use App\Contracts\ResponseBuilderContract;
use Illuminate\Support\Facades\Lang;

class ResponseBuilder implements ResponseBuilderContract
{
    private int $status;

    private ?string $message;

    private mixed $data;

    private mixed $errors;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->status = 200;
        $this->message = 'OK';
        $this->data = [];
        $this->errors = [];

        return $this;
    }

    public function status($status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function message($message): self
    {
        $this->message = (Lang::has($message) ? __($message) : $message);

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function data($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function errors($errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function build()
    {
        $response = new Response($this);
        $this->reset();

        return $response;
    }

    public function toResponse($request)
    {
        return $this->build()->toResponse($request);
    }
}
