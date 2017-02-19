<?php

namespace App\Http\Middleware;

use App\Table_user;
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

        if(!isset(auth()->user()->tableUsers->table_id)) {
            return redirect()->route('userpage');
        }
        return $next($request);
    }
}
