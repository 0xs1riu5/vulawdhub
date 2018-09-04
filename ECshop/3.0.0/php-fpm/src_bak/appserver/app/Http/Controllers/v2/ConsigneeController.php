<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v2\UserAddress;
use App\Models\v2\Features;
use App\Helper\Token;

class ConsigneeController extends Controller {

    /**
    * POST ecapi.consignee.list
    */
    public function index(Request $request)
    {
        $data = UserAddress::getList($this->validated);
        return $this->json($data);
    }

    /**
    * POST ecapi.consignee.add
    */
    public function add(Request $request)
    {
        $rules = [
            'name'      => 'required|string|min:2|max:15',
            'mobile'    => 'numeric|required_without:tel',
            'tel'       => 'string|required_without:mobile',
            'zip_code'  => 'numeric',
            'region'    => 'required|integer|min:1',
            'address'   => 'required|string',
            'identity'  => 'string|min:2|max:19',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }
        $data = UserAddress::add($this->validated);

        return $this->json($data);
    }

    /**
    * POST ecapi.consignee.delete
    */
    public function remove(Request $request)
    {
        $rules = [
            'consignee' => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = UserAddress::remove($this->validated);
        return $this->json($data);
    }

    /**
    * POST ecapi.consignee.update
    */
    public function modify(Request $request)
    {
        $rules = [
            'consignee' => 'required|integer|min:1',
            'name'      => 'required|string|min:2|max:15',
            'mobile'    => 'numeric|required_without:tel',
            'tel'       => 'string|required_without:mobile',
            'zip_code'  => 'numeric',
            'region'    => 'required|integer|min:1',
            'address'   => 'required|string',
            'identity'  => 'string|min:2|max:19',
        ];


        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = UserAddress::modify($this->validated);

        return $this->json($data);
    }

    /**
    * POST ecapi.consignee.setDefault
    */
    public function setDefault(Request $request)
    {

        $rules = [
            'consignee' => 'required|integer|min:1',
        ];

        if($res = Features::check('address.default'))
        {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }



        $data = UserAddress::setDefault($this->validated);

        return $this->json($data);
    }
}
