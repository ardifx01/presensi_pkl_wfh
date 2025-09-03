<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isMaintenanceMode()) {
            return redirect()->route('maintenance');
        }

        return $next($request);
    }

    private function isMaintenanceMode(): bool
    {
        $maintenanceFile = storage_path('framework/maintenance');
        return file_exists($maintenanceFile);
    }
}
