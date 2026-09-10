<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
     * Daftar list yang dimiliki oleh user (owner).
     */
    public function ownedLists(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskList::class, 'user_id');
    }

    /**
     * Daftar list tempat user berpartisipasi sebagai kolaborator.
     */
    public function collaboratingLists(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TaskList::class, 'list_user', 'user_id', 'list_id')
            ->withPivot('role')
            ->withTimestamps();
    }
}
