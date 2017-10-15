<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function apiSuccess($payload = null, $request = null, $pagination = null, $statusCode = 200) {
    	$data = [
    		'data'		  => $payload
    	];

        if($pagination != null) $data['pagination'] = $pagination;
    	if($request != null) $data['request'] = $request;

        $data['diagnostic'] = $this->getDiagnostic();

    	return response($data, $statusCode)
                  ->header('Content-Type', 'application/json');
    }

    public function apiError($statusCode = 400, $internalMsg, $userMsg) {
    	$data['error'] = [
    		'internalMsg'	=> $internalMsg,
    		'userMsg'		=> $userMsg,
    	];

    	return response($data, $statusCode)
                  ->header('Content-Type', 'application/json');
    }

    public function getPagination($data, $totalResult, $currentPage, $limit) {
    	if(count($data) == 0) {
    		return null;
    	}
    	$totalPage = ceil($totalResult / $limit);
    	$nextPage = ($currentPage == $totalPage) ? null : $currentPage + 1;
    	$prevPage = ($currentPage == 1) ? null : $currentPage - 1;

    	return [
    		'totalResult'	=> $totalResult,
    		'perPage'		=> $limit,
    		'totalPage'		=> $totalPage,
    		'currentPage'	=> $currentPage,
    		'nextPage'		=> $nextPage,
    		'prevPage'		=> $prevPage
    	];
    }

    private function getDiagnostic() {
        $duration = microtime() - START_TIME;
        $hours = (int)($duration/60/60);
        $minutes = (int)($duration/60)-$hours*60;
        $seconds = $duration-$hours*60*60-$minutes*60;
        return [
            'server_time'               => date('Y-m-d H:i:s'),
            'server_execution_time'     => $seconds." seconds",
        ];
    }
}
