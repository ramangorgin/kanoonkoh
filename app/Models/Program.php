<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'title', 'description', 'date',
        'has_transport',
        'departure_place_tehran', 'departure_time_tehran', 'departure_lat_tehran', 'departure_lon_tehran',
        'departure_place_karaj', 'departure_time_karaj', 'departure_lat_karaj', 'departure_lon_karaj',
        'required_equipment', 'required_meals',
        'is_free', 'member_cost', 'guest_cost',
        'card_number', 'sheba_number', 'card_holder', 'bank_name',
        'report_photos',
        'is_registration_open', 'registration_deadline',
        'leader_id', 'assistant_leader_id', 'technical_manager_id', 'guide_id', 'support_id',
    ];

    protected $casts = [
        'report_photos' => 'array',
        'date' => 'date',
        'registration_deadline' => 'datetime',
        'has_transport' => 'boolean',
        'is_free' => 'boolean',
        'is_registration_open' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function leader() { return $this->belongsTo(User::class, 'leader_id'); }
    public function assistant_leader() { return $this->belongsTo(User::class, 'assistant_leader_id'); }
    public function technical_manager() { return $this->belongsTo(User::class, 'technical_manager_id'); }
    public function guide() { return $this->belongsTo(User::class, 'guide_id'); }
    public function support() { return $this->belongsTo(User::class, 'support_id'); }
}

