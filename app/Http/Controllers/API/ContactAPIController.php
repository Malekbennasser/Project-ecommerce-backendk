<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\ContactNotificationAdmin;
use App\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Support\Facades\Validator;

class ContactAPIController extends Controller
{
    public function store(Request $request)
{
    // Validez les données du formulaire de contact
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // Créez un tableau avec les données du formulaire
    $formData = [
        'name' => $request->name,
        'email' => $request->email,
        'message' => $request->message,
    ];
   
    FacadesNotification::route('mail', 'malekben0001@gmail.com')
->notify(new ContactNotificationAdmin($formData));

    return response()->json(['message' => 'Formulaire de contact soumis avec succès'], 201);
}
}
