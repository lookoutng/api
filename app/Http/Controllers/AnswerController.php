<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;

class AnswerController extends Controller
{
    ####CREATE ANWWR FOR QUESTION
    public function store(Request $request,$question_id){
        $user = $request->user();
        $request->validate([
            'body' => 'string',
            'status' => 'Integer',
            'is_edited' => 'Integer'
        ]);

        if(Answer::where('question_id',$question_id)->where('user_id',$user->id))
        {
            $response = [
                'message' => "You've already answer the question"
            ];
            return response($response, 402);
        }

        $request->merge([
            'user_id' => $user->id,
            'question_id' => $question_id
        ]);

        Answer::create($request->all());

        $response = [
            'message' => 'Answer created succesfully'
        ];
        return response($response,201);
    }

    #UPDATE USER
    public function update(Request $request,$id){

        $answer = Answer::find($id);
        $user = $request->user();
        $request->validate(
            [
                'body' => 'string',
                'status' => 'Integer',
            ]
        );

        $answer->update([
            'body' => $request->input('body') ?? $answer->body,
            'status' => $request->input('status') ?? $answer->status
        ]);

        $response = [
            'answer' => $answer,
            'message' => 'Answer Updated Succesfully'
        ];
        return response($response,201);
    }


    #DELETE AN ANSWER
    public function delete($id)
    {
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

    #EDITED VERSION OF THE ANSWER
    #NOO MORE USEFUL.....UPDATE MODEL IS APPLIED
    public function previous(Request $request,$id){
        $user = $request->user();
        $answers = Answer::where('user_id',$user->id)->where('question_id', Answer::find($id)->question_id);
        
        $response = [
            'answers' => $answer,
            'message' => "previous answer version"
        ];

        return response($response, 201);
    }
}
