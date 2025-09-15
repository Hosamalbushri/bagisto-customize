<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Webkul\DeliveryAgents\Contracts\DeliveryAgent as DeliveryAgentContract;

class DeliveryAgent extends Authenticatable implements DeliveryAgentContract, JWTSubject
{
    protected $table = 'delivery_agents';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'image',
        'api_token',
        'token',
        'status',
        'password',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    public function getNameAttribute(): string
    {
        return ucfirst($this->first_name).' '.ucfirst($this->last_name);
    }

    public function image_url()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    public function emailExists($email): bool
    {
        $results = $this->where('email', $email);

        if ($results->count() === 0) {
            return false;
        }

        return true;
    }

    public function ranges(): HasMany
    {
        return $this->hasMany(Range::class, 'delivery_agent_id', 'id')->with('state_area');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(DeliveryAgentOrder::class, 'delivery_agent_id');
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(DeliveryAgentReview::class, 'delivery_agent_id');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
