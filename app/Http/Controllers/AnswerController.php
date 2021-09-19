<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;

class AnswerController extends Controller
{
    ####Create Answer
    public function store($request,$id){
        $user = $request->user();
        $request->validate([
            // 'question_id' => 'required|Integer' ,
            'body' => 'string|required',
            'edited_id' => 'required|string',
            'status' => 'required|Integer',
        ]);

        $request->merge([
            'user_id' => $user->id,
            'question_id' => $id
        ]);

        Answer::create($request->all());
    }

    ###Update Answer
    public function update(Request $request){
        $datas = $request->validate([
            'id' => 'Integer',
            'body' => 'string',
            'edited_id' => 'string',
            'status' => 'Integer',
        ]);
        extract($datas);

        $answer = Answer::find($id);
        $answer->update([
            'body' => $body ??  $answer->body,
            'edited_id' => $edited_id ??  $answer->edited_id,
            'status' => $status ??  $answer->status,
        ]);
    }


    ###delete Answer
    public function delete($id){
        $answer = $answer::find($id);
        if(!$answer || $answer->user_id != auth()->user()->id)
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }
        
        $answer->delete();

        $response = [
            'message' => "Answer deleted"
        ];
        return response($response, 201);
    }
}
