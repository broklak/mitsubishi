<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderHead;

class DashboardController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

	public function doGraph(Request $request) {
		$order = new OrderHead();
		$month = ($request->input('month')) ? $request->input('month') : date('m');
        $year = ($request->input('year')) ? $request->input('year') : date('Y');

        $result = $order->graphDo();

        $data = [
        	'month'	=> $month,
        	'year'	=> $year,
        	'page'	=> 'do-graph',
        	'title' => 'Dashboard',
        	'result' => $result
        ];
		return view('dashboard.do', $data);
	}
}
