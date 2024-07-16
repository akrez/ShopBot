<?php

namespace App\Contracts;

use Illuminate\Contracts\Support\Responsable;

interface ResponseBuilderContract extends Responsable
{
    public function status($status): self;

    public function getStatus();

    public function message($message): self;

    public function getMessage();

    public function data($data): self;

    public function getData();

    public function errors($errors): self;

    public function getErrors();

    public function build();

    public function toResponse($request);
}
