<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackMail;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FeedbacksController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::all();
        return response()->json([
            'feedbacks' => $feedbacks,
        ], 200);
    }

    public function getOne($id)
    {
        $feedback = Feedback::find($id);
        if ($feedback) {
            return response()->json([
                'feedback' => $feedback
            ], 200);
        } else {
            return response()->json([
                'message' => 'Feedback not found !'
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
                'feedback' => 'required|max:3000',
            ],
            [
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'feedback.required' => 'Le champ Feedback est obligatoire.',
                'feedback.max' => 'La longueur du Feedback est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 422);
        } else {
            $feedback = Feedback::find($id);
            if ($feedback) {
                $feedback->name = $request->input('name');
                $feedback->email = $request->input('email');
                $feedback->feedback = $request->input('feedback');
                $feedback->save();
                return response()->json([
                    'message' => 'Feedback updated',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Feedback not found!'
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
                'feedback' => 'required|max:3000',
            ],
            [
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'feedback.required' => 'Le champ Feedback est obligatoire.',
                'feedback.max' => 'La longueur du Feedback est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 400);
        } else {
            $feedback = new Feedback;
            $feedback->name = $request->input('name');
            $feedback->email = $request->input('email');
            $feedback->feedback = $request->input('feedback');
            $feedback->save();

            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)
                    ->send(new FeedbackMail($feedback));
            }
            return response()->json([
                'message' => 'Feedback added successfully',
            ], 200);
        }
    }

    public function destroy($id)
    {

        $feedback = Feedback::find($id);

        if ($feedback) {
            $feedback->delete();
            return response()->json([
                'message' => 'Feedback Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Feedback not found !',
            ], 404);
        }
    }
}
