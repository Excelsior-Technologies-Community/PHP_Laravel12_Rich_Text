<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RichText extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'tags',
        'featured_image',
        'is_published',
        'version',
        'original_id'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getExcerptAttribute()
    {
        $cleanContent = strip_tags($this->content);
        return strlen($cleanContent) > 150 
            ? substr($cleanContent, 0, 150) . '...' 
            : $cleanContent;
    }

    public function getWordCountAttribute()
    {
        return str_word_count(strip_tags($this->content));
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }


    public function versions()
    {
        return $this->hasMany(RichText::class, 'original_id', 'id')->orderBy('version', 'desc');
    }
}