<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceCategory extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'description'];

    public function resources()
    {
        return $this->hasMany(Resource::class, 'category_id');
    }
}
