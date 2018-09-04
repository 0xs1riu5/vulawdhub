<?php
//
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\v2\Features;
use App\Models\v2\Coupon;

class CouponController extends Controller
{
    //POST  ecapi.coupon.list
    public function index()
    {
        $rules = [
            'page'          => 'required|integer|min:1',
            'per_page'      => 'required|integer|min:1',
            'status'        => 'required|integer',
            'total_price'   => 'integer',
            'total_amount'  => 'integer',
            'goods'         => 'json',
        ];

        if($res = Features::check('coupon'))
        {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Coupon::getList($this->validated);

       return $this->json($model);
    }

    //POST  ecapi.coupon.available
    public function available()
    {
        $rules = [
            'page'          => 'required|integer|min:1',
            'per_page'      => 'required|integer|min:1',
            'total_price'   => 'required',
            'shop'          => 'integer',
        ];

        if($res = Features::check('coupon'))
        {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = Coupon::getAvailable($this->validated);

       return $this->json($model);
    }
}