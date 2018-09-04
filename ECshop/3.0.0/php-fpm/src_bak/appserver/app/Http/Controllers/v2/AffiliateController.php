<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v2\AffiliateLog;

class AffiliateController extends Controller {

    /**
    * POST ecapi.recommend.affiliate.list
    */
    public function index(Request $request)
    {
        $rules = [
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = AffiliateLog::getList($this->validated);
        return $this->json($model);
    }

    /**
    * POST ecapi.recommend.affiliate.info
    */
    public function info(Request $request)
    {
        $data = AffiliateLog::info();
        return $this->json($data);
    }
}
