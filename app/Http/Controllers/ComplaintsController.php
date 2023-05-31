<?php

namespace App\Http\Controllers;

use App\Mail\ComplaintMail;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ComplaintsController extends Controller
{
    public function index()
    {
        $complaints = Complaint::all();
        return response()->json([
            'complaints' => $complaints,
        ], 200);
    }

    public function getOne($id)
    {
        $complaint = Complaint::find($id);
        if ($complaint) {
            return response()->json([
                'complaint' => $complaint
            ], 200);
        } else {
            return response()->json([
                'message' => 'complaint not found !'
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
                'complaint' => 'required|max:3000',
            ],
            [
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'complaint.required' => 'Le champ Complaint est obligatoire.',
                'complaint.max' => 'La longueur du Complaint est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 422);
        } else {
            $complaint = Complaint::find($id);
            if ($complaint) {
                $complaint->name = $request->input('name');
                $complaint->email = $request->input('email');
                $complaint->complaint = $request->input('complaint');
                $complaint->save();
                return response()->json([
                    'message' => 'Complaint updated',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Complaint not found!'
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
                'complaint' => 'required|max:3000',
            ],
            [
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'complaint.required' => 'Le champ Complaint est obligatoire.',
                'complaint.max' => 'La longueur du Complaint est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 400);
        } else {
            $complaint = new Complaint;
            $complaint->name = $request->input('name');
            $complaint->email = $request->input('email');
            $complaint->complaint = $request->input('complaint');
            $complaint->save();

            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)
                    ->send(new ComplaintMail($complaint));
            }

            return response()->json([
                'message' => 'Complaint added successfully',
            ], 200);
        }
    }

    public function destroy($id)
    {

        $complaint = Complaint::find($id);

        if ($complaint) {
            $complaint->delete();
            return response()->json([
                'message' => 'Complaint deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Complaint not found !',
            ], 404);
        }
    }
}
