<?php 

namespace App\Models;

use CodeIgniter\Model;

class UnitSatuanModel extends Model
{
    protected $table      = 'unit_satuan';
    protected $primaryKey = 'Unit_id';
    protected $allowedFields = ['jenis_unit'];
}
