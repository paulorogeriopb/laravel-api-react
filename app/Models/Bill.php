<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Bill extends Model implements Auditable
{
    use HasFactory, AuditingAuditable;



    use HasFactory;

    protected $table = 'bills';

    protected $fillable = ['name', 'bill_value', 'due_date'];

}
