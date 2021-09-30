<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Location;
use App\Models\User;
use App\Models\Option;

class QuestionController extends Controller
{
   public function index(Request $request){

    // Mata Add the sql Query here
    //Remember say if you want to get the question na the last edited you go get
    //Even if the uer don edit m 3 times...you grab??
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
        ]);

        extract($datas);

        $question = Question::create([
            'type' => $type,
            'user_id' => $user->id,
            'body' => $body,
        ]);

        $user->points -= 30; 
        $user->save();

        $response = [
            'question' => $question,
            'message' => 'Question asked Successfully'
        ];
        return response($response, 201);
        
   }

   public function show(Request $request,$id){

       $question = Question::find($id);
       $answers = Answer::where('question_id',$question->id)->orderby('id', 'DESC')->get();
    
       foreach($answers as $answer){
           $answer->user = User::find($answer->user_id);
       }
       if($question->user_id != $request->user()->id){
        $response = [
            'message' => 'Question Not found' 
        ];
        return response($response, 404);

       }

       $response = [
        'answers' => $answers,
        // 'options' => Option::where('question_id',$question->id)->orderby('id','DESC')->get(),
        'question' => $question,
        'message' => 'Question '.$question->id 
    ];
    return response($response, 201);

   }

   public function myQuestion(Request $request){
        $user = $request->user();
        $questions = Question::where('user_id',$user->id)->where('edited_id',0)->orderby('id','DESC')->get();

        foreach($questions as $question){
            $answer_count = Answer::where('question_id',$question->id)->count();
            $question->answers = $answer_count;
        }
        $response = [
            'question' => $questions,
            'message' => 'Your Recent Question'
        ];
        return response($response, 201);
   }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $datas = $request->validate(
            [
                'body' => 'string',
            ]
        );

        extract($datas);
        $question = Question::find($id);

        //Authorization
       if($question->user_id != $request->user()->id)
       {
            $response = [
                'message' => 'Question Not found' 
            ];
            return response($response, 404);
        }

        $new_question = Question::create(
            [
                'body' => $body,
                'user_id' => $user->id,
                'type' => 1,
                'edited_id' => $question->id
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

    public function previous(Request $request,$id){
        $user = $request->user();
        $questions = Question::where('user_id',$user->id)->where('question_id', $id);
        
        $response = [
            'questions' => $questions,
            'message' => "previous Question version"
        ];

        return response($response, 201);
    }
}