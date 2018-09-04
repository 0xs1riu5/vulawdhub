<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v2\BonusType;
use App\Models\v2\Features;
use App\Helper\Token;


class CashGiftController extends Controller {

    /**
    * POST ecapi.cashgift.list
    */
    public function index(Request $request)
    {
        $rules = [
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
            'status'    => 'required|integer',
        ];

        if($res = Features::check('cashgift'))
        {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = BonusType::getListByUser($this->validated);

        return $this->json($model);
    }

    /**
    * POST ecapi.cashgift.available
    */
    public function available(Request $request)
    {
        $rules = [
            'page'          => 'required|integer|min:1',
            'per_page'      => 'required|integer|min:1',
            'total_price'   => 'required|numeric|min:0',
        ];

        if($res = Features::check('cashgift'))
        {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = BonusType::getAvailableListByUser($this->validated);

        return $this->json($model);
    }

}
