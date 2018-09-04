<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\v2\Configs;

class ConfigController extends Controller {

    public function index()
    {
        $data = Configs::getList();
        return $this->json($data);
    }
   
}
