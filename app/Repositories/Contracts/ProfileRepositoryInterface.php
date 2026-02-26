<?php

namespace App\Repositories\Contracts;

interface ProfileRepositoryInterface
{
    public function findByUserId($userId);
    public function updateProfile($userId, array $data);
    public function incrementXP($userId, $amount);
    public function getTopProducers($limit = 5);
}
