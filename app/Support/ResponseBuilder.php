<?php

namespace App\Support;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Lang;

class ResponseBuilder implements Responsable
{
    private int $status;

    private ?string $message;

    private mixed $data;

    private mixed $errors;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): self
    {
        $this->status = 200;
        $this->message = '';
        $this->data = [];
        $this->errors = [];

        return $this;
    }

    public function status(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function message(?string $message): self
    {
        $this->message = (Lang::has($message) ? __($message) : $message);

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function data(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function errors(mixed $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrors(): mixed
    {
        return $this->errors;
    }

    public function isSuccessful(): bool
    {
        return $this->getStatus() >= 200 and $this->getStatus() < 300;
    }

    public function toResponse($request): \Illuminate\Http\Response
    {
        return response([
            'message' => $this->getMessage(),
            'data' => $this->getData(),
            'errors' => $this->getErrors(),
        ], $this->getStatus());
    }
}
