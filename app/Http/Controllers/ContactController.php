<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $messages = Contact::all();
        return response()->json([
            'messages' => $messages,
        ], 200);
    }

    public function getOne($id)
    {
        $message = Contact::find($id);
        if ($message) {
            return response()->json([
                'message' => $message
            ], 200);
        } else {
            return response()->json([
                'message' => 'Contact not found !'
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
                'subject' => 'required|max:191',
                'message' => 'required|max:3000',
            ],
            [
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'subject.required' => 'Le champ Sujet est obligatoire.',
                'subject.max' => 'La longueur du Sujet est trop longue. La longueur maximale est de 30.',
                'message.required' => 'Le champ Message est obligatoire.',
                'message.max' => 'La longueur du message est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 422);
        } else {
            $message = Contact::find($id);
            if ($message) {
                $message->name = $request->input('name');
                $message->email = $request->input('email');
                $message->subject = $request->input('subject');
                $message->message = $request->input('message');
                $message->save();
                return response()->json([
                    'message' => 'Message updated',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Message not found!'
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
                'subject' => 'required|max:30',
                'message' => 'required|max:3000',
            ],
            [
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'subject.required' => 'Le champ Sujet est obligatoire.',
                'subject.max' => 'La longueur du Sujet est trop longue. La longueur maximale est de 30.',
                'message.required' => 'Le champ Message est obligatoire.',
                'message.max' => 'La longueur du message est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 400);
        } else {
            $message = new Contact;
            $message->name = $request->input('name');
            $message->email = $request->input('email');
            $message->subject = $request->input('subject');
            $message->message = $request->input('message');
            $message->save();

            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)
                    ->send(new ContactMail($message));
            }

            return response()->json([
                'message' => 'Contact added successfully',
            ], 200);
        }
    }

    public function destroy($id)
    {

        $message = Contact::find($id);

        if ($message) {
            $message->delete();
            return response()->json([
                'message' => 'Message Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Message not found !',
            ], 404);
        }
    }
}
