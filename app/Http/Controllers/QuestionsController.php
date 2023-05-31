<?php

namespace App\Http\Controllers;

use App\Mail\QuestionMail;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class QuestionsController extends Controller
{
    public function index()
    {
        $questions = Question::all();
        return response()->json([
            'questions' => $questions,
        ], 200);
    }

    public function getOne($id)
    {
        $question = Question::find($id);
        if ($question) {
            return response()->json([
                'question' => $question
            ], 200);
        } else {
            return response()->json([
                'message' => 'Question not found !'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:191',
                'email' => 'required|max:191',
                'tel' => 'required|max:30',
                'question' => 'required|max:3000',
            ],
            [
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'tel.required' => 'Le champ Phone est obligatoire.',
                'tel.max' => 'La longueur du Phone est trop longue. La longueur maximale est de 30.',
                'question.required' => 'Le champ Question est obligatoire.',
                'question.max' => 'La longueur du Question est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 422);
        } else {
            $question = Question::find($id);
            if ($question) {
                $question->name = $request->input('name');
                $question->email = $request->input('email');
                $question->tel = $request->input('tel');
                $question->Question = $request->input('question');
                $question->save();
                return response()->json([
                    'message' => 'Question updated',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Question not found!'
                ], 404);
            }
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:191',
                'email' => 'required|max:191',
                'tel' => 'required|max:30',
                'question' => 'required|max:3000',
            ],
            [
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'tel.required' => 'Le champ Phone est obligatoire.',
                'tel.max' => 'La longueur du Phone est trop longue. La longueur maximale est de 30.',
                'question.required' => 'Le champ Question est obligatoire.',
                'question.max' => 'La longueur du Question est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 400);
        } else {
            $question = new Question;
            $question->name = $request->input('name');
            $question->email = $request->input('email');
            $question->tel = $request->input('tel');
            $question->question = $request->input('question');
            $question->save();

            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)
                    ->send(new QuestionMail($question));
            }

            return response()->json([
                'message' => 'Question added successfully',
            ], 200);
        }
    }

    public function destroy($id)
    {

        $question = Question::find($id);

        if ($question) {
            $question->delete();
            return response()->json([
                'message' => 'Question Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Question not found !',
            ], 404);
        }
    }
}
