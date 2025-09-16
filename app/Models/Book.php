<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //use SoftDeletes;

    protected $table = 'books';
    protected $guarded = [];
    protected $primaryKey = 'book_id';
    public $timestamps = false;

    protected $fillable = [
      'title',
      'published_at'
    ];

    public function authors(){
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id','author_id');
    }

    public function genres(){
        return $this->belongsToMany(Genre::class, 'book_genres', 'book_id', 'genre_id');
    }
}
