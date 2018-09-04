<?php

namespace App\Models\v2;

use App\Models\BaseModel;

class VolumePrice extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'volume_price';

    public    $timestamps = false;

    protected $visible = ['volume_number', 'volume_price'];

    // protected $appends = ['volume_number', 'volume_price'];

    protected $guarded = [];


}