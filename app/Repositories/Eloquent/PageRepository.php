<?php

namespace App\Repositories\Eloquent;

use App\Models\Page;
use App\Repositories\Contracts\PageRepositoryInterface;

class PageRepository implements PageRepositoryInterface
{
    protected $model;

    public function __construct(Page $page)
    {
        $this->model = $page;
    }

    public function getAll()
    {
        $query = $this->model->select('id', 'title', 'type', 'isActive');

        return $query->get();
    }

    public function getByType(string $type)
    {
        $query = $this->model->where('type', $type);
        return $query->first();
    }

    public function updateOrCreate(string $type, array $data)
    {


        return $this->model->updateOrCreate(
            [
                'type' => $type,
            ],
            [
                'title'       => $data['title'],
                'description' => $data['description'],
                'isActive'    => $data['isActive'] ?? 1, // Default to 1 if not provided
            ]
        );
    }
}
