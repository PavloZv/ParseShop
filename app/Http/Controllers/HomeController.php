<?php

namespace App\Http\Controllers;
use App\SiteData;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController
{
    public function home()
    {
        $content = SiteData::all()->toArray();

        return view('layouts.home', ['content' => $content]);
    }
}
