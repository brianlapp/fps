<?php

namespace App\Http\Controllers;


use App\Mail\SendContactForm;
use App\Models\User;
use App\Rules\NevebounceRule;
use App\Rules\ReCaptcha;
use App\Services\OngageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class HealthController
 * @package App\Http\Controllers
 */
class ContactController extends Controller
{

    public function showForm(): Response
    {
        return Inertia::render('Contact')->withViewData(['meta' => [
            'title' => 'Contact Us',
        ]]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3'],
            'recaptcha_response' => ['required', new ReCaptcha()],
            'email' => ['required', 'lowercase', 'email', 'max:100', new NevebounceRule()],
            'message' => ['required', 'string'],
            'topic' => ['required', 'string']
        ]);

        try {
            $email = new SendContactForm(
                $request->get('topic','Contact'),
                $request->get('name'),
                $request->get('email'),
                $request->get('message')
            );
            $recipient = config('mail.recipients')[strtolower($request->get('topic','Contact'))] ?? config('mail.recipients.contact');
            Mail::to($recipient)->send($email);
        } catch (\Exception $exception) {
            Log::error('[Contact Form Submit]: '. $exception->getMessage());
            /**
             * @var $r \GuzzleHttp\Psr7\Response
             */
        }
        return Redirect::back()->with('message', 'Contact Form Submitted!');

    }

}
