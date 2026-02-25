<?php

namespace App\Repositories\Contracts;

interface PageRepositoryInterface
{
    public function getAll();

    public function getByType(string $type);

    public function updateOrCreate(string $type, array $data);
}
