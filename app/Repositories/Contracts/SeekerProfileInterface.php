<?php 

namespace App\Repositories\Contracts;

interface SeekerProfileInterface
{
    public function findByUserId(string $userId);
    public function updateOrCreate(string $userId, array $data);
}