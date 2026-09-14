<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityStory extends Model
{
    use HasFactory;

    protected $table = 'community_stories';
    protected $primaryKey = 'id_stories';

    protected $fillable = [
        'title',
        'slug',
        'label',
        'excerpt',
        'body',
        'image',
        'topic',
        'material',
        'featured',
        'is_active',
        'sort_order',
    ];

    public function comments()
    {
        return $this->hasMany(CommunityStoryComment::class, 'story_id', 'id_stories');
    }
}
