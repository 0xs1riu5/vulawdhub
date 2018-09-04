<?php

namespace App\Models\v2;
use App\Models\BaseModel;

class Avatar extends BaseModel
{
    protected $connection = 'shop';
    protected $table      = 'avatar';
    public    $timestamps = false;
    protected $guarded = [];
}
