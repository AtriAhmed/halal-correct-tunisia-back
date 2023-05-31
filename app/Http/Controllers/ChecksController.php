<?php

namespace App\Http\Controllers;

use App\Mail\CheckMail;
use App\Models\Check;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ChecksController extends Controller
{

    public function index()
    {
        $checks = Check::all();
        return response()->json([
            'checks' => $checks
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:191',
                'email' => 'required|max:30',
                'company' => 'required|max:30',
                'position' => 'required|max:30',
                'country' => 'required|max:30',
                'question' => 'required|max:3000',
                'used_in' => 'required|max:191',
                'image' => 'required|max:2048',
            ],
            [
                'name.required' => 'Le champ Id catégorie est obligatoire.',
                'name.max' => 'La longueur du Id catégorie est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ email est obligatoire.',
                'email.max' => 'La longueur du email est trop longue. La longueur maximale est de 191.',
                'company.required' => 'Le champ Nom est obligatoire.',
                'company.max' => 'La longueur du Nom est trop longue. La longueur maximale est de 191.',
                'position.required' => 'Le champ Marque est obligatoire.',
                'position.max' => 'La longueur du Marque est trop longue. La longueur maximale est de 20.',
                'country.required' => 'Le champ Prix de vente est obligatoire.',
                'country.max' => 'La longueur du Prix de vente est trop longue. La longueur maximale est de 20.',
                'question.required' => 'Le champ Prix d\'origine est obligatoire.',
                'question.max' => 'La longueur du Prix d\'origine est trop longue. La longueur maximale est de 20.',
                'used_in.required' => 'Le champ Quantité est obligatoire.',
                'used_in.max' => 'La longueur du Quantité est trop longue. La longueur maximale est de 191.',
                'image.required' => 'l\'image est obligatoire.',
                'image.max' => 'La longueur du Image est trop longue. La longueur maximale est de 2048.'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 422);
        } else {
            $check = new Check;
            $check->name = $request->input('name');
            $check->email = $request->input('email');
            $check->company = $request->input('company');
            $check->position = $request->input('position');
            $check->country = $request->input('country');
            $check->question = $request->input('question');
            $check->used_in = $request->input('used_in');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('upload/product/', $filename);
                $check->image = 'upload/product/' . $filename;
            }

            $check->save();

            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)
                    ->send(new CheckMail($check));
            }

            return response()->json([
                'message' => 'ajout avec succès',
            ], 200);
        }
    }

    public function edit($id)
    {
        $check = Check::find($id);
        if ($check) {
            return response()->json([
                'check' => $check
            ], 200);
        } else {
            return response()->json([
                'message' => 'Ckeck not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:191',
                'email' => 'required|max:30',
                'company' => 'required|max:30',
                'position' => 'required|max:30',
                'country' => 'required|max:30',
                'question' => 'required|max:3000',
                'used_in' => 'required|max:30',
            ],
            [
                'name.required' => 'Le champ Id catégorie est obligatoire.',
                'name.max' => 'La longueur du Id catégorie est trop longue. La longueur maximale est de 191.',
                'email.required' => 'Le champ email est obligatoire.',
                'email.max' => 'La longueur du email est trop longue. La longueur maximale est de 191.',
                'company.required' => 'Le champ Nom est obligatoire.',
                'company.max' => 'La longueur du Nom est trop longue. La longueur maximale est de 191.',
                'position.required' => 'Le champ Marque est obligatoire.',
                'position.max' => 'La longueur du Marque est trop longue. La longueur maximale est de 20.',
                'country.required' => 'Le champ Prix de vente est obligatoire.',
                'country.max' => 'La longueur du Prix de vente est trop longue. La longueur maximale est de 20.',
                'question.required' => 'Le champ Prix d\'origine est obligatoire.',
                'question.max' => 'La longueur du Prix d\'origine est trop longue. La longueur maximale est de 20.',
                'used_in.required' => 'Le champ Quantité est obligatoire.',
                'used_in.max' => 'La longueur du Quantité est trop longue. La longueur maximale est de 191.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ], 422);
        } else {
            $check =  Check::find($id);
            if ($check) {
                $check->name = $request->input('name');
                $check->email = $request->input('email');
                $check->name = $request->input('name');
                $check->position = $request->input('position');

                $check->country = $request->input('country');
                $check->question = $request->input('question');
                $check->used_in = $request->input('used_in');

                if ($request->hasFile('image')) {
                    $path = $check->image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('upload/product/', $filename);
                    $check->image = 'upload/product/' . $filename;
                }
                $check->update();

                return response()->json([
                    'message' => 'Check updated',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Check not found',
                ], 404);
            }
        }
    }

    public function destroy($id)
    {

        $check = Check::find($id);

        if ($check) {
            $check->delete();
            return response()->json([
                'message' => 'Complaint Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Complaint not found !',
            ], 404);
        }
    }
}
