<?php
namespace App\Http\Controllers;
use App\Models\JourFerie;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $holidays = JourFerie::all();
        return view('calendar', compact('holidays'));
    }
}