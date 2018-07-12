<?php

namespace Ts\Models;

use Ts\Bases\Model;

/**
 * 用户部门模型.
 */
class Department extends Model
{
    protected $table = 'department';

    protected $primaryKey = 'department_id';

    protected $softDelete = false;
}
