<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;


#[Fillable(['name', 'email', 'password', 'phone', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasRoles, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * URL foto profil siap pakai — mendukung URL foto Google (avatar
     * berupa URL penuh) maupun file yang diunggah manual ke storage lokal.
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->avatar) {
                return null;
            }

            if (Str::startsWith($this->avatar, ['http://', 'https://'])) {
                return $this->avatar;
            }

            return Storage::disk('public')->url($this->avatar);
        });
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function qurbanOrders()
    {
        return $this->hasMany(QurbanOrder::class);
    }

    public function infaqs()
    {
        return $this->hasMany(Infaq::class);
    }

    public function zakats()
    {
        return $this->hasMany(Zakat::class);
    }
}
