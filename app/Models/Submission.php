<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    public const PENDING_STATUS = 'pending';

    public const INPROGRESS_STATUS = 'in_progress';

    public const DONE_STATUS = 'done';

    protected $guarded = [];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patiente()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
