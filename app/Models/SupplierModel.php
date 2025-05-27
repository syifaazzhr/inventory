<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'supplier';
    protected $primaryKey       = 'supplier_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_supplier',
        'nama_supplier',
        'ket_id',
        'alamat_supplier',
        'kota_supplier',
        'no_telp_supplier',
        'no_hp_supplier',
        'kontak_supplier',
        'ntop_supplier'
    ]; 

    public function generateKode()
    {
        $lastSupplier = $this->select('kode_supplier')->orderBy('kode_supplier', 'DESC')->first();

        if ($lastSupplier) {
            $lastKode = $lastSupplier['kode_supplier'];
            $number = (int) substr($lastKode, 1) + 1; // Ambil angka setelah 'S' dan tambah 1
        } else {
            $number = 1; // Jika belum ada data, mulai dari S001
        }

        return 'S' . str_pad($number, 3, '0', STR_PAD_LEFT); // Format menjadi S001, S002, dst.
    }

    public function getSuppliers($search = null, $perPage = 10, $page = 1)
    {
        $query = $this->select('supplier.*, ket_supplier.ket_jenis as ket_jenis')
            ->join('ket_supplier', 'ket_supplier.ket_id = supplier.ket_id', 'left'); 

        if ($search) {
            $query->groupStart()
                ->like('supplier.kode_supplier', $search)
                ->orLike('supplier.nama_supplier', $search)
                ->orLike('ket_supplier.ket_jenis', $search)
                ->orLike('supplier.alamat_supplier', $search)
                ->orLike('supplier.kota_supplier', $search)
                ->orLike('supplier.no_telp_supplier', $search)
                ->orLike('supplier.no_hp_supplier', $search)
                ->orLike('supplier.kontak_supplier', $search)
                ->orLike('supplier.ntop_supplier', $search)
                ->groupEnd();
        }

        // Gunakan paginate untuk mengambil data dengan pagination
        return $query->paginate($perPage, 'default', $page);
    }
 
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = []; 
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}