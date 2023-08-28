<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class Session extends Model
{
    protected $appends = ['expires_at', 'payload_decode', 'user_agent_detected'];

    public function isExpired()
    {
        return $this->last_activity < Carbon::now()->subMinutes(config('session.lifetime'))->getTimestamp();
    }

    public function getExpiresAtAttribute()
    {
        return Carbon::createFromTimestamp($this->last_activity)->addMinutes(config('session.lifetime'))->toDateTimeString();
    }

    public function getPayloadDecodeAttribute()
    {
        return unserialize(base64_decode($this->payload));
    }

    public function getUserAgentDetectedAttribute()
    {
        $agent = new Agent();
        $agent->setUserAgent($this->user_agent);

        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);

        return "$browser $browserVersion";
    }
}
