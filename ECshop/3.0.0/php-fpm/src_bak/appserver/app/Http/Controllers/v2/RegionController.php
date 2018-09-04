<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v2\Region;

class RegionController extends Controller {

    public function index(Request $request)
    {
        $response = Region::getList();
        return $this->json($response);
    }
}
