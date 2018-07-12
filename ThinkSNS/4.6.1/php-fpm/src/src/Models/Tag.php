<?php

namespace Ts\Models;

use Ts\Bases\Model;

/**
 * 标签模型.
 *
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class Tag extends Model
{
    protected $table = 'tag';

    protected $primaryKey = 'tag_id';

    protected $softDelete = false;
} // END class Tag extends Model
