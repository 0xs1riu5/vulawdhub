<?php

namespace App\Models\v2;
use App\Models\BaseModel;

class Sns extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'sns';
    protected $primaryKey = 'user_id';
    public    $timestamps = true;
}
