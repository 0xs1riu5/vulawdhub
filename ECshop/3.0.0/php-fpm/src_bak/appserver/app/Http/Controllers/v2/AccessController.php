<?php
//
namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseModel;
use App\Helper\XXTEA;
use Log;

class AccessController extends Controller
{

    public function dns()
    {
        $hosts = json_decode(config('app.hosts'), true);
        return $this->json(['hosts' => $hosts]);
    }

    public function batch()
    {
        $rules = [
            'batch' => 'required|json',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $batch = json_decode($this->validated['batch'], true);

        if (!is_array($batch)) {
            return $this->json(BaseModel::formatError(10000));
        }

        $batch_data = [];

        foreach ($batch as $key => $value) {
            
            $header_arr = [];
            if ($headers = @json_decode($value['header'], true)) {
                foreach ($headers as $header_key => $header_value) {
                    $header_arr[] = $header_key.': '.$header_value;
                }
            }

            $res = curl_request(url($value['name']), $value['method'], @json_decode($value['data'], true), $header_arr);

            if (isset($res['error']) && $res['error']) {
                $res['is_batch'] = 1;

                return $this->json($res);
            }

            $batch_data[] = [
                'seq' => $value['seq'],
                'name' => $value['name'],
                'data' => $res
            ];
        }

        return $this->json(['batch' => $batch_data]);
    }
}
