<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    public $table = 'mail';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'body',
        'send_date',
        'sent_date',
        'sent_id',
    ];
}
