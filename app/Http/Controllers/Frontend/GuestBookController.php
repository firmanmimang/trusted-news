<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\GuestStoreRequest;
use App\Models\GuestBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuestBookController extends Controller
{
    public function __invoke()
    {
        return view('pages.frontend.guest-book');
    }

    public function store(GuestStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            GuestBook::create([
                'name' => $request->nama,
                'gender' => $request->kelamin,
                'age' => $request->umur,
                'phone_number' => $request->nomor_telepon,
                'email' => $request->email,
                'message' => $request->pesan_dan_saran,
                'rating' => $request->rating,
            ]);
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_create', ['type' => "Masukan buku tamu"]));
            return back()->with('alert', ['type' => AlertHelper::ALERT_SUCCESS, 'message' => trans('success.crud_create', ['type' => "Masukan buku tamu"])]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back()->withErrors(['500' => trans('server.500')]);
        }
    }
}
