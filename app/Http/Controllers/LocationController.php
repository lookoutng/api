<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Location;

class LocationController extends Controller
{
    public function store(Request $request){
        $datas = $request->validate([
            'parent_id' => 'required|Integer',
            'lat' => 'required|double',
            'long' => 'required|double',
            'type' => 'required|Integer' #User is 0 Question is 1
        ]);
        extract($datas);

        Location::create($request->all());
    }

    public function show(){
        
    }
    //
}
