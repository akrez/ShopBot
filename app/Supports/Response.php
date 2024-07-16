<?php

namespace App\Supports;

use App\Contracts\ResponseBuilderContract;
use Illuminate\Contracts\Support\Responsable;

class Response implements Responsable
{
    public $status;

    public $message;

    public $data;

    public $errors;

    public function __construct(ResponseBuilderContract $builder)
    {
        $this->status = $builder->getStatus();
        $this->message = $builder->getMessage();
        $this->data = $builder->getData();
        $this->errors = $builder->getErrors();
    }

    public function toResponse($request)
    {
        return response([
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ], $this->status);
    }
}
