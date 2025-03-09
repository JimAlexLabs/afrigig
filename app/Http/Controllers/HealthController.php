<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    public function check()
    {
        $health = [
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'services' => [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
                'storage' => $this->checkStorage(),
            ],
        ];

        $isHealthy = !in_array('error', array_column($health['services'], 'status'));

        return response()->json($health, $isHealthy ? 200 : 503);
    }

    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'ok',
                'message' => 'Database connection successful',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed',
            ];
        }
    }

    private function checkCache()
    {
        try {
            $key = 'health_check_' . now()->timestamp;
            Cache::put($key, true, 1);
            $value = Cache::get($key);

            return [
                'status' => $value === true ? 'ok' : 'error',
                'message' => $value === true ? 'Cache is working' : 'Cache test failed',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache test failed',
            ];
        }
    }

    private function checkStorage()
    {
        try {
            $key = 'health_check_' . now()->timestamp;
            Storage::put($key, 'test');
            $exists = Storage::exists($key);
            Storage::delete($key);

            return [
                'status' => $exists ? 'ok' : 'error',
                'message' => $exists ? 'Storage is working' : 'Storage test failed',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Storage test failed',
            ];
        }
    }
} 