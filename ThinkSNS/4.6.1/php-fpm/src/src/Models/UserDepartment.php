<?php

namespace Ts\Models;

use Ts\Bases\Model;

class UserDepartment extends Model
{
    protected $table = 'user_department';

    protected $primaryKey = 'uid';

    protected $softDelete = false;

    public function department()
    {
        return $this->hasOne('Ts\\Models\\Department', 'department_id', 'department_id');
    }
}
