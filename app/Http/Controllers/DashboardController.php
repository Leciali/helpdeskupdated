<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardview(){
        return view('indexDashboard');
    }

    function openticketview(){
        return view('indexOpenTicket');
    }

    function pendingticketview(){
        return view('indexPendingTicket');
    }

    function solvedticketview(){
        return view('indexSolvedTicket');
    }

    function reportview(){
        return view ('indexReport');
    }
}