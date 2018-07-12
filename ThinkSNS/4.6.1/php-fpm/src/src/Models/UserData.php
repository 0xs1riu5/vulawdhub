<?php

namespace Ts\Models;

use Ts\Bases\Model;

class UserData extends Model
{
    protected $table = 'user_data';

    protected $primaryKey = 'id';

    protected $softDelete = false;
} // END class UserData extends Model
