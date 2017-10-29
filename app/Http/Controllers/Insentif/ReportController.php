<?php

namespace App\Http\Controllers\Insentif;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliveryOrder;
use App\Models\CarType;
use App\Models\CarModel;
use App\Models\OrderHead;
use App\Models\OrderApproval;
use App\User;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function insentif(Request $request)
    {

        $start = ($request->input('start')) ? $request->input('start') : date('Y-m-01');
        $end = ($request->input('end')) ? $request->input('end') : date('Y-m-d');

        $startWord = date('j F Y', strtotime($start));
        $endWord = date('j F Y', strtotime($end));

        $report = new DeliveryOrder();
        $result = $report->getInsentif($start, $end);
        $data = [
            'page' => 'insentif',
            'title' => "Insentif Report $startWord To $endWord",
            'result' => $result,
            'start' => $start,
            'end' => $end,
            'startWord' => $startWord,
            'endWord' => $endWord,
            'startTime' => strtotime($start),
            'endTime' => strtotime($end)
        ];
        return view("report.insentif", $data);
    }

    public function order(Request $request) {
        $start = ($request->input('start')) ? $request->input('start') : date('Y-m-01');
        $end = ($request->input('end')) ? $request->input('end') : date('Y-m-d');

        $startWord = date('j F Y', strtotime($start));
        $endWord = date('j F Y', strtotime($end));

        $where[] = ['date', '>=', $start];
        $where[] = ['date', '<=', $end];
        $report = new OrderHead();

        $data = [
            'result' => OrderHead::where($where)->get(),
            'page' => 'order',
            'title' => "SPK Report $startWord To $endWord",
            'start' => $start,
            'end' => $end,
            'startWord' => $startWord,
            'endWord' => $endWord,
            'startTime' => strtotime($start),
            'endTime' => strtotime($end)
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

    public function excelOrder(Request $request) {
        $start = $request->input('start');
        $end = $request->input('end');

        $startWord = date('Y-m-d', $start);
        $endWord = date('Y-m-d', $end);

        $where[] = ['date', '>=', $startWord];
        $where[] = ['date', '<=', $endWord];

        $data = OrderHead::where($where)->get();

        $csv = [];
        foreach($data as $key => $value){
            $csv[$key]['SPK Number'] = $value['spk_code'];
            $csv[$key]['SPK Control Number'] = $value['spk_doc_code'];
            $csv[$key]['Sales Name'] = User::getName($value['created_by']);
            $csv[$key]['Date'] = date('j F Y', strtotime($value->date));
            $csv[$key]['Customer'] = $value->customer_name;
            $csv[$key]['Car'] = ($value->type_id == 0) ? CarModel::getName($value->model_id).' '.$value->type_others : CarType::getFullName($value->type_id);
            $csv[$key]['Status'] = str_replace('<br />', '.', OrderApproval::getLabelStatus($value));
        }

        return Excel::create('SPK-Report-'.$startWord.'-'.$endWord, function($excel) use ($csv) {
            $excel->sheet('SPK Report', function($sheet) use ($csv)
            {
                $sheet->fromArray($csv);
            });
        })->export('csv');
    }

    public function excelInsentif(Request $request) {
        $start = $request->input('start');
        $end = $request->input('end');

        $startWord = date('Y-m-d', $start);
        $endWord = date('Y-m-d', $end);

        $where[] = ['date', '>=', $startWord];
        $where[] = ['date', '<=', $endWord];

        $report = new DeliveryOrder();
        $data = $report->getInsentif($startWord, $endWord);

        $csv = [];
        foreach($data as $key => $value){
            $csv[$key]['Sales Name'] = User::getName($key);
            $csv[$key]['Flee Sales'] = $value['fleet'];
            $csv[$key]['Non Fleet Sales'] = $value['non_fleet'];
            $csv[$key]['Total Car Sales'] = $value['sales'];
            $csv[$key]['Total Insentif'] = moneyFormat($value['total_insentif']);
            $csv[$key]['Total Imbalan'] =  moneyFormat($value['total_imbalan']);
            $csv[$key]['Sales Accepted'] = moneyFormat($value['sales_accepted']);
        }

        return Excel::create('Insentif-Report-'.$startWord.'-'.$endWord, function($excel) use ($csv) {
            $excel->sheet('Insentif Report', function($sheet) use ($csv)
            {
                $sheet->fromArray($csv);
            });
        })->export('csv');
    }
}
