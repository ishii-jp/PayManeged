<?php

namespace App\Http\Middleware;

use App\Utils\RequestUtil;
use Closure;
use Illuminate\Http\Request;

class RequestParamValidMiddleware
{
    /**
     * クエリパラメーターyearの値が正規表現にマッチしない場合は404にします
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $year = $request->query('year');

        if (is_null($year)) {
            return $next($request);
        }
        
        abort_unless(RequestUtil::RequestParamValid($year, config('match.paramYear')), 404);
        
        return $next($request);
    }
}
