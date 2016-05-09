<?php
namespace App\Http\Middleware;
use Illuminate\Http\Request;
use App\Requests;
use Closure;

class CheckLogin
{
/**
 *Run the request filter.
 *@param  \Illuminate\Http\Request  $request
 *@param  \Closure  $next
 *@return mixed
 **/
    public function handle(Request $request, Closure $next)
    {
        if (''==$request->session()->get('member','')){
            return redirect('/login');
        }

        return $next($request);
    }

}
