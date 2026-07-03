<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'activity', 'description', 'ip_address', 'user_agent'
    ];

    public static function log($activity, $description = null)
    {
        $ip = request()->ip();
        
        // Auto-register IP
        if ($ip) {
            \App\Models\IpTracker::firstOrCreate(['ip_address' => $ip]);
        }

        return self::create([
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
        ]);
    }
}
