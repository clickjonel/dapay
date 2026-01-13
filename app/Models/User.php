<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'prefix',
        'suffix',
        'nickname',
        'username',
        'password',
        'account_status',
        'user_level',
        'pdoho_province_id',
        'program_id'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        $fullName = '';

        if ($this->prefix) {
            $fullName .= $this->prefix . ' ';
        }

        $fullName .= $this->first_name . ' ';

        if ($this->middle_name) {
            $fullName .= $this->middle_name[0] . '. ';
        }

        $fullName .= $this->last_name;

        if ($this->suffix) {
            $fullName .= ', ' . $this->suffix;
        }

        return trim($fullName);
    }

}
