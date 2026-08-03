<?php

namespace App\Models;

use App\Traits\LogsAdminActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes, LogsAdminActivity, HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'location',
        'speaker_name',
        'latitude',
        'longitude',
        'start_at',
        'end_at',
        'thumbnail',
        'poster',
        'registration_status',
        'status',
        'is_featured',
        'views_count',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function documentations()
    {
        return $this->hasMany(EventDocumentation::class);
    }

    /**
     * Menghitung status waktu kegiatan secara otomatis (TIDAK disimpan di database).
     */
    public function getTimeStatusAttribute(): string
    {
        $now = now();

        if ($now->lt($this->start_at)) {
            return 'upcoming';
        }

        if ($now->between($this->start_at, $this->end_at)) {
            return 'ongoing';
        }

        return 'finished';
    }

    public function getTimeStatusLabelAttribute(): string
    {
        return match ($this->time_status) {
            'upcoming' => 'Akan Datang',
            'ongoing' => 'Sedang Berlangsung',
            'finished' => 'Selesai',
        };
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_at', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_at', '<=', now())->where('end_at', '>=', now());
    }

    public function scopeFinished($query)
    {
        return $query->where('end_at', '<', now());
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
