<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class LangController extends Controller
{
    public function change(Request $request)
    {
        $lang = $request['lang'];
        $langs = ['en-us','ar-ae','pt-br'];
        return $lang;
        if(in_array($lang, $langs))
        {
            Session::set('lang',$lang);
            return redirect()->back();
        }
    }
}
