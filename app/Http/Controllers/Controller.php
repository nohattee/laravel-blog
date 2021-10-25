<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function filter($query, $request)
    {
        if ($request->input('filters')) {
            $filters = json_decode($request->input('filters'), true);
            $query = $query->filter($filters);
        }

        if ($request->input('page')) {
            $pageSize = $request->input('page_size', 10);
            $query = $query->paginate($pageSize);
        }
        
        return $query;
    }
}
