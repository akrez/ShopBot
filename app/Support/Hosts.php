<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class Hosts
{
    public function __construct(protected $filePath) {}

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getArray()
    {
        $filePath = $this->getFilePath();

        if (! File::exists($filePath)) {
            return [];
        }

        return (array) File::json($filePath);
    }

    public function getArrayKeys()
    {
        return array_keys($this->getArray());
    }

    public function hostToBlogId($host)
    {
        $hosts = $this->getArray();

        $id = Arr::get($hosts, $host);
        if ($id) {
            return $id;
        }

        $id = Arr::get($hosts, 'www.'.$host);
        if ($id) {
            return $id;
        }

        return null;
    }
}
