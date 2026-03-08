<?php

namespace App\Repositories\Contracts;

interface StemRepositoryInterface
{
    public function getLibraryStems($filters = []);
    public function uploadStem($categoryId, array $data);
    public function updateStem($stemId, array $data); // Added this
    public function logInteraction($stemId, $userId, $type);
    public function deleteStem($stemId);
}
