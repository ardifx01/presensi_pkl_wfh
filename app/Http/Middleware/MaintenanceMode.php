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
        // Cek apakah maintenance mode aktif dengan file flag
        $flagPath = storage_path('framework/maintenance');
        
        if (file_exists($flagPath)) {
            // Dapatkan path saat ini
            $currentPath = $request->path();
            
            // Jika sudah berada di halaman maintenance, lanjutkan
            if ($currentPath === 'maintenance') {
                return $next($request);
            }
            
            // Izinkan akses untuk admin jika sudah login
            if (auth()->check() && auth()->user()->is_admin) {
                return $next($request);
            }
            
            // Izinkan route API maintenance status
            if ($currentPath === 'api/maintenance/status') {
                return $next($request);
            }
            
            // Izinkan akses login untuk admin
            if ($currentPath === 'login' || $currentPath === 'logout') {
                return $next($request);
            }
            
            // Redirect ke halaman maintenance untuk semua request lainnya
            return redirect('/maintenance');
        }

        return $next($request);
    }
}
