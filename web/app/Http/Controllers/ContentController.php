<?php

namespace App\Http\Controllers;

use App\Services\SystemResourceService;

class ContentController extends Controller
{

    public function showSupportPage()
    {
        return view('_pages.support');
    }

}
