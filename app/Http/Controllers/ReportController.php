<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Report;

class ReportController extends Controller
{
    public function store(Request $request, $id){
        $user = $request->user();
        $request->validate(
            [
                'summary' => 'string|required'
            ]
        );
        Report::create(
            [
                'summary' => "User ".$user->id." reports"." answer ".$id." on issue ".$request->input('summary')
            ]
        );

        return response(
            [
                'message' => "Answer reported",
            ],
            201
        );
    }
}
