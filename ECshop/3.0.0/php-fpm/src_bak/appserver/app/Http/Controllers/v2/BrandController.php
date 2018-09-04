<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v2\Brand;

class BrandController extends Controller {

    /**
    * POST ecapi.brand.list
    */
    public function index(Request $request)
    {
        $rules = [
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Brand::getList($this->validated);

        return $this->json($model);
    }

    /**
    * POST ecapi.recommend.brand.list
    */
    public function recommend(Request $request)
    {
        $rules = [
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Brand::getListByOrder($this->validated);

        return $this->json($model);
    }
}
