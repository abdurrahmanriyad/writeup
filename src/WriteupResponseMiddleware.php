<?php

namespace Abdurrahmanriyad\Writeup;

use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Support\Facades\Log;

class WriteupResponseMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
    public function terminate($request, $response)
    {
        try {
            $response_time = (microtime(true) - LARAVEL_START) * 1000;

            if (config('writeup.response_log.time')) {
                Log::info('Writeup request response time : ' . $response_time . ' milliseconds');
            }
        } catch (Exception $exception) {
            Log::error("Writeup couldn't write a log. Please report to package owner");
        }
    }
}
