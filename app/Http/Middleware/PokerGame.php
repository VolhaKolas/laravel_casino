<?php

namespace App\Http\Middleware;

use Closure;

class PokerGame
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //Сделай нормальный мидлвар, чтобы заходило только со страницы userpage

        if($_SERVER['HTTP_REFERER'] == 'http://poker/userpage') {

            return $next($request);
        }
        else {
            return redirect()->route('userpage');
        }
    }
}
