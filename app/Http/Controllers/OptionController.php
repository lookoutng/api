<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Option;

class OptionController extends Controller
{

    ####Create Option
    public function store($request,$id){
        $user = $request->user();
        $request->validate([
            'body' => 'string|required',
            'question_id' => 'required|string',
        ]);

        Option::create($request->all());
    }

    ###Update Option
    public function update(Request $request){
        $datas = $request->validate([
            'body' => 'string',
            'question_id' => 'Integer',
        ]);
        extract($datas);

        $Option = Option::find($id);
        $Option->update([
            'body' => $body ??  $Option->body,
            'question_id' => $question_id ??  $Option->question_id,
        ]);
    }


    ###delete Option
    public function delete($id){
        $Option = $Option::find($id);
        if(!$Option || $Option->user_id != auth()->user()->id)
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }
        
        $Option->delete();

        $response = [
            'message' => "Option deleted"
        ];
        return response($response, 201);
    }
}