<?php

namespace App\Http\Controllers\Insentif;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliveryOrder;
use App\Models\OrderHead;

class ReportController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function insentif(Request $request)
    {
        $month = ($request->input('month')) ? $request->input('month') : date('m');
        $year = ($request->input('year')) ? $request->input('year') : date('Y');
        $report = new DeliveryOrder();
        $result = $report->getInsentif($month, $year);
        $data = [
            'page' => 'insentif',
            'title' => "Insentive Report ".date('F', strtotime("$year-$month-01"))." $year" ,
            'result' => $result,
            'month' => $month,
            'year' => $year,
        ];
        return view("report.insentif", $data);
    }

    public function order(Request $request) {
        $month = ($request->input('month')) ? $request->input('month') : date('m');
        $year = ($request->input('year')) ? $request->input('year') : date('Y');

        $start = $year.'-'.$month.'-01';
        $end = $year.'-'.$month.'-'.date('t', strtotime($start));
        $where[] = ['date', '>=', $start];
        $where[] = ['date', '<=', $end];
        $report = new OrderHead();

        $data = [
            'result' => $report->list(false, null, 'desc', 100000000000, 1, null, $month, $year),
            'page' => 'order',
            'title' => "SPK Report ".date('F', strtotime("$year-$month-01"))." $year" ,
            'month' => $month,
            'year' => $year,
        ];
        return view("report.order", $data);
    }

    public function delivery(Request $request) {
        $month = ($request->input('month')) ? $request->input('month') : date('m');
        $year = ($request->input('year')) ? $request->input('year') : date('Y');

        $start = $year.'-'.$month.'-01';
        $end = $year.'-'.$month.'-'.date('t', strtotime($start));
        $where[] = ['do_date', '>=', $start];
        $where[] = ['do_date', '<=', $end];
        $report = new DeliveryOrder();

        $data = [
            'result' => $report->list($where),
            'page' => 'do',
            'title' => "DO Report ".date('F', strtotime("$year-$month-01"))." $year" ,
            'month' => $month,
            'year' => $year,
        ];
        return view("report.delivery", $data);
    }
}
