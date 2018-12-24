<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DocViewer extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
       
    }
    public function index()
    {
        // $user = Auth::user();
        // if($user->cannot("search"))
        // {
        //    return redirect('/');
        // }
        return view('docsTable');
    }
    public function upload()
    {
        return view('upload');
    }
}
