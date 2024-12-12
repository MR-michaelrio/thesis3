<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'id_company', 'title', 'description', 'start_time', 'end_time', 'background_color', 'border_color', 'text_color', 'status', 'holiday'
    ];

    public function labels()
    {
        return $this->belongsToMany(EventLabel::class, 'event_labels_mapping', 'event_id', 'label_id');
    }
}
