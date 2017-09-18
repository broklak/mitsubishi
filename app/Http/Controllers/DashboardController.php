<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderHead;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

	public function doGraph(Request $request) {
		if(!Auth::user()->can('read.dashboard')) {
			return redirect(route('order.index'));
		}
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
