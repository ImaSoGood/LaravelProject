<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    //use SoftDeletes;

    protected $table = 'genres';
    protected $primaryKey = 'genre_id';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function books(){
        return $this->belongsToMany(Book::class, 'book_genres', 'genre_id', 'book_id');
    }
}
