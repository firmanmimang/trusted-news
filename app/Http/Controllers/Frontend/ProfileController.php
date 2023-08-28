<?php
 
namespace App\Http\Controllers\Frontend;

use App\Helpers\AlertHelper;
use App\Helpers\ParseUrlHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProfilePasswordUpdateRequest;
use App\Http\Requests\Frontend\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return view('pages.frontend.profile', [
            'active_session' => auth()->user()->sessions,
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $validatedData = $request->toArray();
        $user = auth()->user();
        try {
            DB::beginTransaction();
            if($request->file('photo_profile')){
                $file = $request->file('photo_profile');
                $validatedData['image'] = ParseUrlHelper::ParseUrl($user->replaceImage($file));
                $user->image = $validatedData['image'];
                $user->save();
            }
            $user->update([
                'name' => $validatedData['nama'],
                'username' => $validatedData['username'],
            ]);
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "Profile"]));
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function updatePassword(ProfilePasswordUpdateRequest $request)
    {
        try {
            $user = auth()->user();

            DB::beginTransaction();
            $user->password = bcrypt($request->password_baru);
            $user->save();
            DB::commit();

            // Auto logout user yang diganti passwordnya
            // Get the user's sessions from the sessions table
            $userSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->get();
            
            // Iterate over the user's sessions and invalidate them
            foreach ($userSessions as $session) {
                Session::getHandler()->destroy($session->id);
            }
            
            Auth::logoutCurrentDevice();
            
            AlertHelper::flashSuccess('Password kamu berhasil diubah silahkan login kembali');
            return redirect()->route('home');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back()->withErrors(['password_sekarang' => trans('server.500')]);
        }
    }

    public function sessionTerminate($payload)
    {
        try {
            DB::beginTransaction();
            $session = auth()->user()->sessions()->where('payload', $payload)->first();
            $session->delete();
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_delete', ['type' => "Session"]));
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);
            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function stream(Request $request)
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $listener = new \Qruto\Wave\Listener($request);

        Event::listen('task-progress', function ($event) use ($listener) {
            $listener->send(json_encode([
                'turboStream' => [
                    [
                        'target' => 'task-progress',
                        'content' => view('tasks.progress', ['message' => $event->message])->render(),
                    ],
                ],
            ]));
        });

        // Keep the connection alive
        while (true) {
            echo "data: " . json_encode(['ping' => 'pong']) . "\n\n";
            ob_flush();
            flush();
            sleep(1);
        }
    }
}
