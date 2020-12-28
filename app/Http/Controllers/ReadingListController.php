<?php

namespace App\Http\Controllers;

use App\Models\ReadingList;
use Illuminate\Http\Request;

class ReadingListController extends Controller
{
    function index(Request $request)
    {
        $rlObj = new ReadingList();
        $readingList = $rlObj->getAllReadingList(auth()->user());

        return view('home', ['readingList' => $readingList]);
    }
}
