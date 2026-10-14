<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class KnowledgeBaseController extends Controller
{
    public function index(): View
    {
        return view('knowledge-base.index');
    }
}