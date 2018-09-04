<?php

namespace App\Models\v2;
use App\Models\BaseModel;

class Cert extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'cert';
    public    $timestamps = true;
}
