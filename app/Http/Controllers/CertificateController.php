<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Certificate;
use App\Models\Feedback;

class CertificateController extends Controller
{
    /**
     * Display the authenticated user's certificates.
     */
    public function view_certificate()
    {
        $userId = Auth::id();
        $certificates = Certificate::where('user_id', $userId)->get();

        return view('view_certificate', compact('certificates'));
    }

    /**
     * Store feedback from the user.
     */
    public function post_feedback(Request $request)
    {
        $request->validate([
            'vaccine_name' => 'required|string',
            'feedback' => 'required|string|min:10',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'vaccine_name' => $request->vaccine_name,
            'feedback' => $request->feedback,
        ]);

        return redirect()->back()->with('message', 'Thank you for your feedback!');
    }
}
