<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v2\AreaCode;

class AreaCodeController extends Controller
{
    /**
    * POST ecapi.areacode.list
    */
    public function index(Request $request)
    {

        $model = AreaCode::getList();

        return $this->json($model);
    }

}
