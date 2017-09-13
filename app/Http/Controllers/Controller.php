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

    public function apiSuccess($payload = null, $request = null, $pagination = null) {
    	$data = [
    		'request'	=> $request,
    		'data'		=> $payload
    	];

    	if($pagination != null) $data['pagination'] = $pagination;

    	return response($data, 200)
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
}
