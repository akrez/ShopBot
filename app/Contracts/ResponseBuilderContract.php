<?php

namespace App\Contracts;

use Illuminate\Contracts\Support\Responsable;

interface ResponseBuilderContract extends Responsable
{
    public function status(int $status): self;

    public function getStatus(): int;

    public function message(?string $message): self;

    public function getMessage(): ?string;

    public function data(mixed $data): self;

    public function getData(): mixed;

    public function errors(mixed $errors): self;

    public function getErrors(): mixed;

    public function build();

    public function toResponse($request);
}
