<?php

namespace App\Support;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Lang;

class ResponseBuilder implements Responsable
{
    const DEFAULT_STATUS = 200;

    const DEFAULT_MESSAGE = '';

    private int $status;

    private string $message;

    private mixed $data;

    private mixed $input;

    private ?MessageBag $errors;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): self
    {
        $this->status = static::DEFAULT_STATUS;
        $this->message = static::DEFAULT_MESSAGE;
        $this->data = null;
        $this->input = null;
        $this->errors = null;

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

    public function message(string $message): self
    {
        $this->message = (Lang::has($message) ? __($message) : $message);

        return $this;
    }

    public function getMessage(): string
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

    public function input(mixed $input): self
    {
        $this->input = $input;

        return $this;
    }

    public function getInput(): mixed
    {
        return $this->input;
    }

    public function errors(?MessageBag $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrors(): ?MessageBag
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

    public static function new(
        $status = self::DEFAULT_STATUS,
        $message = self::DEFAULT_MESSAGE
    ): static {
        return (new static)
            ->status($status)
            ->message($message);
    }
}
