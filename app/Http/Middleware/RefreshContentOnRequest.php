<?php

namespace App\Http\Middleware;

use App\Support\ContentApi;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshContentOnRequest
{
    /**
     * A pull-to-refresh loads the page with ?refresh=1, which makes the app ask the website for fresh content instead of using the 5-minute copy.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && $request->boolean('refresh') && ! $request->hasHeader('X-Livewire')) {
            ContentApi::refresh();
        }

        return $next($request);
    }
}
