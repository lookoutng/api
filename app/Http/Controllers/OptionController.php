<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Option;
use App\Models\User;
use App\Models\Question;

class OptionController extends Controller
{

    ####Create Option
    public function store(Request $request){
        $user = $request->user();
        $datas = $request->validate([
            'body' => 'string|required',
            'question_id' => 'required|string',
        ]);

        extract($datas);
        if(Question::find($question_id)->user_id != $user->id)
        {
            $response = [
                'message' => 'Question Not found' 
            ];
            return response($response, 404);

        }

        Option::create([
            'body' => $body,
            'question_id' => $question_id
        ]);

        $response = [
            'message' => 'Option Created for Question '.$question_id 
        ];
        return response($response, 201);
    }

    ###Update Option
    public function update(Request $request,$question_id){
        $id = $question_id;
        $datas = $request->validate([
            'body' => 'string',
        ]);
        extract($datas);

        $Option = Option::find($id);
        if(Question::find($option->question_id)->user_id != $user->id)
        {
            $response = [
                'message' => 'Question Not found' 
            ];
            return response($response, 404);

        }

        $Option->update([
            'body' => $body ??  $Option->body,
        ]);
    }


    ###delete Option
    public function delete(Request $request,$id){
        $option = Option::find($id);
        if(!$option || Question::find($option->question_id)->user_id != $request->user()->id)
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }
        
        $option->delete();

        $response = [
            'message' => "Option deleted"
        ];
        return response($response, 201);
    }
}