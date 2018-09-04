<?php
//
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\v2\ShopConfig;

class SiteController extends Controller
{
    //POST  ecapi.site.get
    public function index()
    {
       return $this->json(ShopConfig::getSiteInfo());
    }
}