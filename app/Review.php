<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
      //
    protected $table = 'review';
    protected $fillable = ['user_id','product_id','order_id','rating','komentar'];
}
