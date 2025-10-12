<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Portfolio;

class EnsureUserOwnsPortfolio
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $portfolioId = $request->route('id') ?? $request->route('portfolio');
        
        if ($portfolioId) {
            $portfolio = Portfolio::findOrFail($portfolioId);
            
            if ($portfolio->user_id !== auth()->id()) {
                abort(403, 'Anda tidak memiliki akses ke portfolio ini.');
            }
        }

        return $next($request);
    }
}