<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['path', 'sender_id'];

    protected $appends = ['sender'];

    /**
     * A user who sent a file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getSenderAttribute()
    {
        return $this->sender()->first();
    }
}
