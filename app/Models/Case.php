<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    protected $table = 'cases';
    
    protected $fillable = ['case_number', 'title', 'type', 'offense', 'date_filed', 'status', 'prosecutor_id', 'investigating_officer_id', 'agency_station', 'notes'];
    
    public function prosecutor()
    {
        return $this->belongsTo(Prosecutor::class);
    }

    public function hearings()
    {
        return $this->hasMany(Hearing::class);
    }

    public function parties()
    {
        return $this->hasMany(CaseParty::class);
    }

    public function caseNotes()
    {
        return $this->hasMany(Note::class);
    }
}