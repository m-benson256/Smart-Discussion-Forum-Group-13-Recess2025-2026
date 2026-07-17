<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;




/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
<<<<<<< HEAD

#[Fillable(['name', 'email', 'password','status','role','verification_status'])]

=======
#[Fillable(['name', 'email', 'password','status','role','verification_statusS'])]
>>>>>>> a71614295232ad323805f7255ab2b0c2a15bcebf
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable 
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */ 
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'academic_category',
        'degree_program',
        'role',
        'status',
        'desk_contact_number',
    ];
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
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
<<<<<<< HEAD

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(
            User_interests::class,
            'member_user_interests',
            'UserID',
            'InterestID'
        )->withTimestamps();
    }
    public function messages()
{
    return $this->hasMany(Message::class);
}

public function reactionsGiven()
{
    return $this->hasMany(MessageReaction::class);
}
=======
>>>>>>> a71614295232ad323805f7255ab2b0c2a15bcebf
}
