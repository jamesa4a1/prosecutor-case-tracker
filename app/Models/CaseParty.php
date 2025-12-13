<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseParty extends Model
{
    protected $table = 'case_parties';
    
    protected $fillable = ['case_id', 'party_type', 'name', 'contact_info', 'role'];

    public function case()
    {
        return $this->belongsTo(CaseModel::class);
    }
}
