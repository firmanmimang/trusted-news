<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;

class EventStreamController extends Controller
{
    // contoh server sent event
    public function stream(){
        return response()->stream(function () {
            while (true) {
                echo "event: ping\n";
                $curDate = date(DATE_ISO8601);
                echo 'data: {"time": "' . $curDate . '"}';
                echo "\n\n";

                $trades = News::latest()->get();
                echo 'data: {"total_trades":' . $trades->count() . '}' . "\n\n";

                $latestTrades = User::latest()->first();
                if ($latestTrades) {
                    echo 'data: {"latest_trade_user":"' . $latestTrades->name . '", "latest_trade_stock":"' . $latestTrades->username . '", "latest_trade_volume":"' . $latestTrades->email . '"}' . "\n\n";
                }

                ob_flush();
                flush();

                // Break the loop if the client aborted the connection (closed the page)
                if (connection_aborted()) {break;}
                usleep(5000000); // 500ms
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
