<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cart item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job associated with the cart item
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Calculate the total cost including premium features
     */
    public function calculateTotalCost(): float
    {
        $total = 0.0;

        if ($this->hide_bid) {
            $total += $this->user->calculatePremiumCost($this->bid_amount);
        }

        if ($this->featured_bid) {
            $total += $this->user->calculatePremiumCost($this->bid_amount);
        }

        $this->premium_cost = $total;
        $this->save();

        return $total;
    }

    /**
     * Check if the cart item has any premium features
     */
    public function hasPremiumFeatures(): bool
    {
        return $this->hide_bid || $this->featured_bid;
    }

    /**
     * Toggle a premium feature
     */
    public function togglePremiumFeature(string $feature): void
    {
        if (!in_array($feature, ['hide_bid', 'featured_bid'])) {
            throw new \InvalidArgumentException('Invalid premium feature');
        }

        $this->$feature = !$this->$feature;
        $this->calculateTotalCost();
        $this->save();
    }

    /**
     * Get the description of premium features
     */
    public function getPremiumFeaturesDescription(): array
    {
        $features = [];

        if ($this->hide_bid) {
            $features[] = [
                'name' => 'Hidden Bid',
                'description' => 'Your bid amount will be hidden from other freelancers',
                'cost' => $this->user->calculatePremiumCost($this->bid_amount)
            ];
        }

        if ($this->featured_bid) {
            $features[] = [
                'name' => 'Featured Bid',
                'description' => 'Your bid will be highlighted and shown at the top of the list',
                'cost' => $this->user->calculatePremiumCost($this->bid_amount)
            ];
        }

        return $features;
    }
} 