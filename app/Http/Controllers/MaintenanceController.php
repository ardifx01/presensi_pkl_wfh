<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
    public function show()
    {
        // Cek apakah maintenance mode benar-benar aktif
        $flagPath = storage_path('framework/maintenance');
        
        if (!File::exists($flagPath)) {
            // Jika maintenance tidak aktif, redirect ke home
            return redirect('/');
        }
        
        return view('maintenance');
    }
    
    public function checkStatus()
    {
        $flagPath = storage_path('framework/maintenance');
        $isActive = File::exists($flagPath);
        
        return response()->json([
            'maintenance_active' => $isActive,
            'timestamp' => now()->toISOString()
        ]);
    }
    
    public function toggle(Request $request, $action)
    {
        // Pastikan hanya admin yang bisa toggle
        if (!auth()->check() || !auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $flagPath = storage_path('framework/maintenance');
        
        try {
            if ($action === 'down') {
                // Enable maintenance
                File::put($flagPath, json_encode([
                    'enabled_at' => now()->toISOString(),
                    'message' => 'Sistem sedang dalam maintenance',
                    'admin_bypass' => true,
                    'enabled_by' => auth()->user()->name
                ]));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Maintenance mode berhasil diaktifkan',
                    'status' => 'enabled'
                ]);
                
            } elseif ($action === 'up') {
                // Disable maintenance
                if (File::exists($flagPath)) {
                    File::delete($flagPath);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Maintenance mode berhasil dinonaktifkan',
                    'status' => 'disabled'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid action'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
