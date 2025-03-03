<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericEmail;
use App\Models\ProiectEmailTrimis;
use Carbon\Carbon;

class EmailController extends Controller
{
    public function sendEmail (Request $request)
    {
        $validated = $request->validate([
            'proiect_id'   => 'required',
            'destinatar_id'   => 'required',
            'destinatar_type'   => 'required',
            'email_destinatar' => 'required|email:rfc,dns',
            'email_subiect'   => 'required|string|max:255',
            'email_mesaj'   => 'required|string',
        ]);

        // Send the email
        Mail::to($validated['email_destinatar'])
            ->send(new GenericEmail($validated['email_subiect'], $validated['email_mesaj']));

        $proiectEmailTrimis = ProiectEmailTrimis::make($validated);
        $proiectEmailTrimis->sent_at = Carbon::now();
        $proiectEmailTrimis->save();

        return redirect()->back()->with('success', 'Email trimis cu succes!');
    }
}
