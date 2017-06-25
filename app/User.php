<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'patronymic', 'description', 'email', 'password', 'department_id',
    ];

    protected $with = ['department'];

    protected $appends = ['department'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * A worker belongs to department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Orders created by user.
     *
     * @return mixed
     */
    public function outgoingOrders()
    {
        return $this->hasMany(Order::class, 'creator_id')
            ->where('closed', false)
            ->orderBy('due_date');
    }

    /**
     * User is responsible for orders.
     *
     * @return mixed
     */
    public function incomingOrders()
    {
        return $this->hasMany(Order::class, 'worker_id')
            ->where('closed', false)
            ->orderBy('due_date');
    }

    public function archivedOrders()
    {
        return $this->archivedIncomingOrders
            ->merge($this->archivedOutgoingOrders);
    }
    
    /**
     * Archived (closed) incoming orders.
     *
     * @return mixed
     */
    public function archivedIncomingOrders()
    {
        return $this->hasMany(Order::class, 'worker_id')
            ->where('closed', 1)
            ->orderBy('due_date');
    }

    /**
     * Archived (closed) outgoing orders.
     *
     * @return mixed
     */
    public function archivedOutgoingOrders()
    {
        return $this->hasMany(Order::class, 'creator_id')
            ->where('closed', 1)
            ->orderBy('due_date');
    }

    /**
     * Check if a user is an administrator.
     *
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    public function getDepartmentAttribute()
    {
        return Department::findOrFail($this->department_id)->first();
    }

    /**
     * Workers who are not admins.
     *
     * @param $query
     * @return mixed
     */
    public function scopeWorkers($query)
    {
        return $query->where('admin', 0);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Create new outgoing order.
     *
     * @param $order
     * @return mixed
     */
    public function createOrder($order)
    {
        return $this->outgoingOrders()->create($order->all());
    }

    /**
     * Get full name of a user.
     *
     * @return string
     */
    public function fullName()
    {
        return $this->surname . " " . $this->name . " " . $this->patronymic;
    }
}
