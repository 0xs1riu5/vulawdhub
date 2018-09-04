<?php

namespace App\Models\v2;
use App\Models\BaseModel;

class Category extends BaseModel
{
    protected $connection = 'shop';
    protected $table      = 'category';
    public    $timestamps = false;
    protected $guarded = [];

}
