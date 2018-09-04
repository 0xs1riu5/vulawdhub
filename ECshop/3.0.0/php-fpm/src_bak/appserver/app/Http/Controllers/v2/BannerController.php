<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v2\Banner;

class BannerController extends Controller {

    /**
    * POST ecapi.banner.list
    */
    public function index(Request $request)
    {
        $model = Banner::getList();

        return $this->json($model);
    }
}
