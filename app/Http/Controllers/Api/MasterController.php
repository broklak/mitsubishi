<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;

class MasterController extends Controller
{
	public function __construct() {
		$this->middleware('auth:api');
	}

    public function news(Request $request) {
    	$token = $request->input('token');
    	$limit = $request->input('limit');
    	$page = $request->input('page');
    	$news = new News();
    	$data = $news->all();

    	return $data;
    }
}
