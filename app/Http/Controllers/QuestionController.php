<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Location;
use App\Models\User;

class QuestionController extends Controller
{
   public function index(Request $request){

    // Mata Add the sql Query here
   } 

   public function store(Request $request){

        $user = $request->user();

        if($user->points < 30){
            $response = [
                'message' => 'Not enough points'
            ];
            return response($response, 402);
        }

        $datas = $request->validate([
            'body' => 'string|required',
            'type' => 'string|required',
            'lat' => 'required',
            'long' => 'required',
        ]);

        extract($datas);

        $question = Question::create([
            'type' => $type,
            'user_id' => $user->id,
            'body' => $body,
        ]);

        $location = Location::create([
            'parent_id' => $question->id,
            'type' => 1, # 1 for question 0 for users 
            'lat' => $lat,
            'long' => $long
        ]);

        $user->points -= 30; 
        $user->save();

        $response = [
            'question' => $questions,
            'message' => 'Question asked Successfully'
        ];
        return response($response, 201);
        
   }

   public function show(Request $request,$id){

       $question = Question::find($id);
       $answers = Answer::where('question_id',$question->id)->get();

       if($question->user_id != $request->user()->id){
        $response = [
            'message' => 'Question Not found' 
        ];
        return response($response, 404);

       }

       $response = [
        'comments' => $answers,
        'question' => $question,
        'message' => 'Question '.$question->id 
    ];
    return response($response, 201);

   }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $datas = $request->validate(
            [
                'body' => 'string',
                'type' => 'string',
            ]
        );

        extract($datas);
        $question = Question::find($id);

        
       if($question->user_id != $request->user()->id)
       {
            $response = [
                'message' => 'Question Not found' 
            ];
            return response($response, 404);
        }

        $new_question =Question::create([
            'body' => $body,
            'user_id' => $user->id,
            'type' => $type,
            'edited_id' => $question->id
        ]);

        $location = Location::find($id);
        $new_location = Location::create(
            [
            'lat' => $lat,
            'long' => $long
            ]
        );

        $response = [
            'question' => $new_question,
            'message' => 'Question Update Successfully'
        ];
        return response($response, 201);
    }

    public function delete($id)
    {

        $question = Question::find($id);

        if(!$question || $question->user_id != auth()->user()->id)
        {
            $response = [
                'message' => "Object does not exist"
            ];
            return response($response, 404);
        }
        
        $question->delete();
        $response = [
            'message' => "question deleted"
        ];

        return response($response, 201);
    }
}