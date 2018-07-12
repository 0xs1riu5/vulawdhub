<?php

namespace Ts\Models;

use Ts\Bases\Model;

/**
 * 勋章模型.
 */
class Medal extends Model
{
    protected $table = 'medal';

    protected $primaryKey = 'id';

    //protected $softDelete = false;

    public function getSrcAttribute()
    {
        return $this->attach->path;
    }

    public function getAttachAttribute()
    {
        $val = explode('|', $this->attributes['src']);

        return Attach::find($val[0]);
    }

    public function getSmallSrcAttribute($val)
    {
        $val = explode('|', $val);

        return $val[1];
    }
}
