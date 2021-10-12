<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Location;
use App\Models\User;
use App\Models\Option;

use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Request $request){

        extract($request->validate([
            'lat' => 'numeric|required',
            'long' => 'numeric|required',
        ]));

        $range = $request->input('range') ?? 3;

        // "SELECT *, @lat1 := SUBSTRING_INDEX(lazy_location, ',', 1) AS `lat`, @lon1 := SUBSTRING_INDEX(SUBSTRING_INDEX(lazy_location, ',', 2), ',', -1) AS `lon`, degrees(acos( sin(radians(@lat1)) * sin(radians(${p1[0]})) +  cos(radians(@lat1)) * cos(radians(${p1[0]})) * cos(radians(@lon1-${p1[1]}))))*60*1.1515 `vendor_dist` FROM `riders` AS d1 LEFT JOIN ( SELECT `user`, $time-start_time `period` FROM `sessions` ORDER BY `period` ) AS d2 ON d1.user=d2.user WHERE d2.user AND vendor_dist <= 3.10686 GROUP BY `id` ORDER BY vendor_dist LIMIT 10"

        $questions = DB::select('SELECT *, degrees(acos( sin(radians(`lat`)) * sin(radians(?)) +  cos(radians(`lat`)) * cos(radians(?)) * cos(radians(`long`-?))))*60*1.1515 AS `dist` FROM `questions` WHERE user_id!=? HAVING dist <= ? ORDER BY dist', [$lat, $lat, $long, $request->user()->id ?? 1, $range]);
        foreach($question as $questions){
            $question->options = Option::where('question_id',$question->id)->get();
        }
        return response([
            'questions' => $questions,
            'message' => 'Task successful'
        ], 201);
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