<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderHead;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
	public function __construct() {
		$this->middleware('auth:api');
	}

    public function list(Request $request) {
    	try {
    		$approval = ($request->input('type') == 'approval') ? true : false;
	    	$limit = ($request->input('limit')) ? $request->input('limit') : 10;
	    	$page = ($request->input('page')) ? $request->input('page') : 1;
	        $sort = ($request->input('sort')) ? $request->input('sort') : 'desc';
	        $query = $request->input('query');

	        if($limit < 1) return $this->apiError($statusCode = 400, 'Limit data must be greater than zero', 'Something went wrong with the request');	

	        $order = new OrderHead();
            $data = $order->list($approval, $query, $sort, $limit, $page);
            $data = $order->filterResult($data);
            $pagination = $this->getPagination($data, $order->countList($approval, $query), $page, $limit);
    	} catch (Exception $e) {
    		return $this->apiError($statusCode = 500, $e->getMessage(), 'Something went wrong');          
    	}

    	return $this->apiSuccess($data, $request->input(), $pagination);
    }
}
