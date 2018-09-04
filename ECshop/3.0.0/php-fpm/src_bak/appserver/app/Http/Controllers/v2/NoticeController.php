<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v2\Card;
use App\Models\v2\Notice;

class NoticeController extends Controller {

    /**
    * POST ecapi.notice.list
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

        $model = Notice::getList($this->validated);

        return $this->json($model);
    }

    /**
    * GET notice.{id:[0-9]+}
    */
    public function show($id)
    {
        return Notice::getNotice($id);
    }
}
