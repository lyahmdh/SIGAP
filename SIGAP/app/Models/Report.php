<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'severity',
        'is_anonymous',
        'status',
        'location_detail',
        'district',
        'latitude',
        'longitude',
        'coordinates',
        'priority_score'
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'priority_score' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ReportImage::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function projectUpdates()
    {
        return $this->hasMany(ProjectUpdate::class);
    }
    
    public function recalculatePriorityScore(): void
    {
        $category = $this->category;

        if (!$category) {
            return;
        }

        $reportCount = self::where('category_id', $this->category_id)
            ->whereRaw("
                ST_Distance_Sphere(
                    POINT(longitude, latitude),
                    POINT(?, ?)
                ) <= ?
            ", [
                $this->longitude,
                $this->latitude,
                $category->report_radius
            ])
            ->distinct('user_id')
            ->count('user_id');

        $severityScore = match ((int) $this->severity) {
            1 => 30,
            2 => 60,
            3 => 100,
            default => 0,
        };

        $reportCountScore =
            min($reportCount, 10) * 10;

        $days =
            now()->diffInDays($this->created_at);

        $waitingScore =
            (min($days, 30) / 30) * 100;

        $priorityScore =
            ($severityScore * 0.3) +
            ($reportCountScore * 0.5) +
            ($waitingScore * 0.2);

        $this->update([
            'priority_score' => round($priorityScore, 2)
        ]);
    }
}