<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityStoryComment extends Model
{
    use HasFactory;

    protected $table = 'community_story_comments';
    protected $primaryKey = 'id_comments';

    protected $fillable = [
        'story_id',
        'customer_id',
        'comment',
    ];

    public function story()
    {
        return $this->belongsTo(CommunityStory::class, 'story_id', 'id_stories');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customers');
    }
}
