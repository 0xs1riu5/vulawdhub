<?php
//
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helper\Token;
use App\Models\v2\Keywords;

class SearchController extends Controller
{
    //POST  ecapi.search.keyword.list
    public function index()
    {
       return $this->json(Keywords::getHot());
    }
}