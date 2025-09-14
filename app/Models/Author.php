<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';
    protected $primaryKey = 'author_id';
    protected $fillable = ['full_name'];
    public $timestamps = false;

    public function books(){
        return $this->belongsToMany(Book::class, 'book_authors', 'author_id','book_id');
    }

}
