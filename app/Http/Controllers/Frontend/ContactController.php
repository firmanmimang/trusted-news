<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('pages.frontend.contact');
    }

    public function store(ContactStoreRequest $request)
    {
        $dataFromRequest = [
            'access_key' => env('W3FORM_KEY'),
            'subject' => "Contact Form Trusted News dari $request->nama",
            'from_name' => "TRUSTED NEWS",
            'botcheck' => false,
            'name' => $request->nama,
            'phone' => $request->nomor_telepon,
            'email' => $request->email,
            'message' => $request->pesan,
        ];

        $url = 'https://api.web3forms.com/submit';

        $headers= [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->post($url, $dataFromRequest);

        $status = $response->status();

        if ($status === 200) {
            AlertHelper::flashSuccess(trans('success.sent', ['type' => "Pesan"]));
            return back();
        } else {
            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }
}
