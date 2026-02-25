<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Page extends Model
{
    use HasUuids;

    protected $table = 'pages';
    protected $keyType = 'string';
    public $incrementing = false;

    // 'id' is guarded, so 'language_id' is automatically fillable
    protected $guarded = ['id'];
}
