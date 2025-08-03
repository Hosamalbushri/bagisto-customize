<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Webkul\DeliveryAgents\Contracts\DeliveryAgent as DeliveryAgentContract;
use Webkul\Sales\Models\OrderProxy;

class DeliveryAgent extends Authenticatable implements DeliveryAgentContract
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
        return $this->hasMany(Range::class, 'delivery_agent_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderProxy::modelClass(), 'delivery_agent_id');
    }
}
