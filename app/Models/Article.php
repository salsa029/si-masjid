<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsAdminActivity;

class Article extends Model
{
    use SoftDeletes, LogsAdminActivity, HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'meta_keyword',
        'status',
        'views_count',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Scope: hanya artikel yang sudah terbit ke publik.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: urutkan berdasarkan jumlah dilihat, dari yang terpopuler.
     */
    public function scopePopular($query)
    {
        return $query->orderByDesc('views_count');
    }

    /**
     * Menambah 1 hitungan dilihat, dipanggil setiap kali halaman detail diakses publik.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
