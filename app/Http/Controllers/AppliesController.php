<?php

namespace App\Http\Controllers;

use App\Mail\ApplyMail;
use App\Models\Apply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AppliesController extends Controller
{
    public function index()
    {
        $applies = Apply::all();
        return response()->json([
            'applies' => $applies,
        ], 200);
    }

    public function getOne($id)
    {
        $apply = Apply::find($id);
        if ($apply) {
            return response()->json([
                'apply' => $apply
            ], 200);
        } else {
            return response()->json([
                'message' => 'Apply not found !'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'company' => 'required|max:191',
                'name' => 'required|max:191',
                'email' => 'required|max:191',
                'tel' => 'required|max:191',
                'office_address' => 'required|max:191',
                'factory_address' => 'required|max:191',
                'explanation' => 'required|max:3000',
            ],
            [
                'company.required' => 'Le champ Company est obligatoire.',
                'company.max' => 'La longueur du Company est trop longue. La longueur maximale est de 191.',
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'office_address.required' => 'Le champ Nom est obligatoire.',
                'office_address.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'factory_address.required' => 'Le champ Nom est obligatoire.',
                'factory_address.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'explanation.required' => 'Le champ Feedback est obligatoire.',
                'explanation.max' => 'La longueur du Feedback est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 422);
        } else {
            $apply = Apply::find($id);
            if ($apply) {
                $apply->company = $request->input('company');
                $apply->name = $request->input('name');
                $apply->email = $request->input('email');
                $apply->tel = $request->input('tel');
                $apply->office_address = $request->input('office_address');
                $apply->factory_address = $request->input('factory_address');
                $apply->explanation = $request->input('explanation');
                $apply->save();
                return response()->json([
                    'message' => 'Apply updated',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Apply not found!'
                ], 404);
            }
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'company' => 'required|max:191',
                'name' => 'required|max:191',
                'email' => 'required|max:191',
                'email' => 'required|max:191',
                'office_address' => 'required|max:191',
                'factory_address' => 'required|max:191',
                'explanation' => 'required|max:3000',
            ],
            [
                'company.required' => 'Le champ Company est obligatoire.',
                'company.max' => 'La longueur du Company est trop longue. La longueur maximale est de 191.',
                'name.required' => 'Le champ Nom est obligatoire.',
                'name.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ Email est obligatoire.',
                'email.max' => 'La longueur du Email est trop longue. La longueur maximale est de 191.',
                'office_address.required' => 'Le champ Nom est obligatoire.',
                'office_address.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'factory_address.required' => 'Le champ Nom est obligatoire.',
                'factory_address.max' => 'La longueur du nom est trop longue. La longueur maximale est de 191.',
                'explanation.required' => 'Le champ Feedback est obligatoire.',
                'explanation.max' => 'La longueur du Feedback est trop longue. La longueur maximale est de 3000.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 400);
        } else {
            $apply = new Apply;
            $apply->company = $request->input('company');
            $apply->name = $request->input('name');
            $apply->email = $request->input('email');
            $apply->tel = $request->input('tel');
            $apply->office_address = $request->input('office_address');
            $apply->factory_address = $request->input('factory_address');
            $apply->explanation = $request->input('explanation');
            $apply->save();

            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)
                    ->send(new ApplyMail($apply));
            }

            return response()->json([
                'message' => 'Apply added successfully',
            ], 200);
        }
    }

    public function destroy($id)
    {

        $apply = Apply::find($id);

        if ($apply) {
            $apply->delete();
            return response()->json([
                'message' => 'Apply Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Apply not found !',
            ], 404);
        }
    }
}
