<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PixabayService
{
    protected $apiKey;
    protected $baseUrl = 'https://pixabay.com/api/';
    protected $lastError = null;

    public function __construct()
    {
        $this->apiKey = config('services.pixabay.key');
        if (empty($this->apiKey)) {
            Log::error('Pixabay API key is not configured');
            $this->lastError = 'API key not configured';
        }
    }

    public function getLastError()
    {
        return $this->lastError;
    }

    protected function validateResponse($response, $query)
    {
        if (!$response->successful()) {
            $error = sprintf(
                'Pixabay API error (Status: %d): %s. Query: %s',
                $response->status(),
                $response->body(),
                $query
            );
            Log::error($error);
            $this->lastError = $error;
            return false;
        }

        $data = $response->json();
        if (empty($data['hits'])) {
            $message = sprintf(
                'No images found for query: %s (Total hits: %d)',
                $query,
                $data['totalHits'] ?? 0
            );
            Log::warning($message);
            $this->lastError = $message;
            return false;
        }

        Log::info('Pixabay API success', [
            'query' => $query,
            'total_hits' => $data['totalHits'] ?? 0,
            'hits_count' => count($data['hits'])
        ]);

        return true;
    }

    public function searchImages($query, $category = null, $perPage = 3)
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $cacheKey = "pixabay_" . md5($query . $category . $perPage);
        
        return Cache::remember($cacheKey, 3600, function () use ($query, $category, $perPage) {
            try {
                $response = Http::timeout(10)->get($this->baseUrl, [
                    'key' => $this->apiKey,
                    'q' => $query,
                    'category' => $category,
                    'per_page' => $perPage,
                    'safesearch' => true,
                    'image_type' => 'photo',
                ]);

                if ($this->validateResponse($response, $query)) {
                    return $response->json()['hits'];
                }
            } catch (\Exception $e) {
                $error = sprintf(
                    'Pixabay API exception: %s. Query: %s',
                    $e->getMessage(),
                    $query
                );
                Log::error($error);
                $this->lastError = $error;
            }

            return [];
        });
    }

    public function getHeroImage()
    {
        $images = $this->searchImages('professional business technology', 'business', 1);
        $this->logImageResult('Hero image', $images);
        return $images[0] ?? null;
    }

    public function getSkillAssessmentImage()
    {
        $images = $this->searchImages('skill assessment test education', 'education', 1);
        $this->logImageResult('Skill assessment image', $images);
        return $images[0] ?? null;
    }

    public function getJobListingImage()
    {
        $images = $this->searchImages('freelance work remote', 'business', 1);
        $this->logImageResult('Job listing image', $images);
        return $images[0] ?? null;
    }

    public function getProfileImage()
    {
        $images = $this->searchImages('professional profile avatar', 'people', 1);
        $this->logImageResult('Profile image', $images);
        return $images[0] ?? null;
    }

    public function getAdminDashboardImage()
    {
        $images = $this->searchImages('dashboard analytics business', 'business', 1);
        $this->logImageResult('Admin dashboard image', $images);
        return $images[0] ?? null;
    }

    protected function logImageResult($type, $images)
    {
        if (empty($images)) {
            Log::warning("$type search failed", ['error' => $this->lastError]);
        } else {
            Log::info("$type search successful", [
                'image_url' => $images[0]['largeImageURL'] ?? null,
                'preview_url' => $images[0]['previewURL'] ?? null
            ]);
        }
    }
} 