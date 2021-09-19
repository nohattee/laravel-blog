<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    /**
     * Admin view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function __invoke(): \Illuminate\Contracts\View\View
    {
        return view('admin');
    }
}
