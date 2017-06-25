<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['worker_id', 'body', 'title', 'due_date', 'important', 'closed'];

    protected $with = ['creator', 'worker', 'files'];

    protected $appends = ['creator', 'worker', 'messages', 'files', 'unreadCount'];

    /**
     * A person who created an order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * A person who will do this order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    /**
     * Messages related to an order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Attachments related to an order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function getCreatorAttribute()
    {
        return $this->creator()->get();
    }

    public function getWorkerAttribute()
    {
        return $this->worker()->get();
    }

    public function getMessagesAttribute()
    {
        return $this->messages()->get();
    }

    public function getFilesAttribute()
    {
        return $this->files()->get();
    }

    /**
     * Attach a file to an order.
     *
     * @param $file
     * @return Model
     */
    public function attachFile($file)
    {
        return $this->files()->create([
            'path' => $file,
            'sender_id' => auth()->id()
        ]);
    }

    /**
     * Add a message to an order.
     *
     * @param $body
     * @return Model
     */
    public function addMessage($body)
    {
        return $this->messages()->create([
            'sender_id' => auth()->id(),
            'body' => $body
        ]);
    }

    /**
     * Close an order (mark as completed / canceled).
     *
     * @return $this
     */
    public function markAsClosed()
    {
        $this->update(['closed' => 1]);
        return $this;
    }

    /**
     * Return count of unread incoming messages for authenticated user.
     *
     * @return int
     */
    public function getUnreadCountAttribute()
    {
        return $this->messages()
            ->where('sender_id', '!=', \Auth::id())
            ->where('read', '=', 0)
            ->count();
    }
}
