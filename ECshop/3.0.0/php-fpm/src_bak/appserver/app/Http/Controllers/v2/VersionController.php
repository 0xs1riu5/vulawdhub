<?php
//
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\v2\Version;

class VersionController extends Controller
{
    /**
     * POST ecapi.version.check
     */
    public function check(Request $request)
    {
    	$data = Version::checkVersion();
        return $this->json($data);
    }

}
