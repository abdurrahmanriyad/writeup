<?php

namespace Abdurrahmanriyad\Writeup;

use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WriteupQueryMiddleware
{
    const DATE_TIME_FORMAT = 'Y/m/d h:m:s a';

    public function handle($request, Closure $next)
    {
        try {
            $data = [];
            DB::listen(function ($query) use ($request, $data) {
                if (config('writeup.query_log.sql')) {
                    $data['sql'] = $query->sql;
                }

                if (config('writeup.query_log.execution_time')) {
                    $data['execution_time'] = $query->time . ' millisecond(s)';
                }

                if (config('writeup.query_log.connection')) {
                    $data['connection'] = $query->connectionName;
                }

                if (config('writeup.query_log.url')) {
                    $data['url'] = $request->fullUrl();
                }

                if (config('writeup.query_log.method')) {
                    $data['method'] = $request->method();
                }

                if (count($data)) {
                    Log::info("Writeup Query", $data);
                }
            });
        } catch (Exception $exception) {
            Log::error("Writeup couldn't write a log. Please report to package owner");
        }

        return $next($request);
    }
}
