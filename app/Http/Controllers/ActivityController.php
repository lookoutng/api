<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function Add($summary,  $group){
        Activity::create([
            'summary' => $summary,
            'group' => $group
        ]);
   }
}
