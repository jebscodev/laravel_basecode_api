<?php

namespace App\Http\Middleware;

use Closure;
use App\Car;
use App\Constants as Constant;

class EnsureCarOwner
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
        $car = $request->route()->parameters();
        $result = Car::ownedByUser()->where('id', $car)->get();

        if (count($result) < 1) {
            return response(
                [
                    'message' => Constant::MSG_NO_DATA_MATCH
                ],
                Constant::HTTP_CODE_NOT_FOUND
            );
        }

        return $next($request);
    }
}
