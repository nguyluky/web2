<?php
// filepath: d:\K2_Y2\web2\app\Http\Controllers\HomeController.php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Xử lý logic trang chủ
        return view('home');
    }
}
?>