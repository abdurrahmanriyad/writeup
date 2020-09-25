<?php

namespace Abdurrahmanriyad\Writeup;

use Closure;
use Exception;
use Illuminate\Support\Facades\Log;

class WriteupRequestMiddleware
{
    const DATE_TIME_FORMAT = 'Y/m/d h:m:s a';

    public function handle($request, Closure $next)
    {
        $data = [];

        try {
            if (config('writeup.request_log.url')) {
                $data['url'] = $request->fullUrl();
            }

            if (config('writeup.request_log.method')) {
                $data['method'] = $request->method();
            }

            if (config('writeup.request_log.method')) {
                $data['header'] = $this->getSupportedHeaderFrom($request);
            }

            if (config()) {
                $data['data'] = $request->all();
            }

            if (count($data)) {
                Log::info("Writeup Request", $data);
            }
        } catch (Exception $e) {
            Log::error("Writeup couldn't write a log. Please report to package owner");
        }

        return $next($request);
    }

    private function getSupportedHeaderFrom($request)
    {
        $headers = collect($request->headers);
        return $headers->only(config('writeup.request_log.header.take'))->toArray();
    }
}
