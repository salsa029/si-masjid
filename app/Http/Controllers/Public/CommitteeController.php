<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use Illuminate\View\View;

class CommitteeController extends Controller
{
    public function index(): View
    {
        $committees = Committee::orderBy('term_start')->get();

        return view('public.committees.index', compact('committees'));
    }
}
