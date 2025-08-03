<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testAdmin()
    {
        return "Admin middleware is working correctly!";
    }
}
