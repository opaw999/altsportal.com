<?php

namespace App\Models\admins;

use App\Models\BaseModel;

class Setup_model extends BaseModel
{
    function __construct()
    {
        parent::__construct();
    }

    public function supplier_list()
    {
        return $this->tk_corp
            ->table('promo_supplier')
            ->get()
            ->getResultArray();
    }

    public function supplier_types()
    {
        return $this->tk_corp
            ->table('promo_supplier_type')
            ->get()
            ->getResultArray();
    }

    public function supplier_account_list()
    {
        return $this->tk_corp->table('promo_supplier_user')
            ->select('*, promo_supplier_user.id as id, promo_supplier_user.status as status')
            ->join('promo_supplier', 'promo_supplier.id = promo_supplier_user.supplier_id')
            ->where('promo_supplier.supplier_type !=', 3)
            ->get()
            ->getResultArray();
    }

    public function users_account()
    {
        return $this->tk_corp->table('promo_supplier_user')
            ->select('*')
            ->join('promo_supplier', 'promo_supplier.id = promo_supplier_user.supplier_id')
            ->get()
            ->getResultArray();
    }

    public function consignor_account_list()
    {
        return $this->tk_corp->table('promo_supplier_user')
            ->select('*, promo_supplier_user.id as id, promo_supplier_user.status as status')
            ->join('promo_supplier', 'promo_supplier.id = promo_supplier_user.supplier_id')
            // ->where('promo_supplier.supplier_type', 3)
            ->get()
            ->getResultArray();
    }

    public function vendor_list()
    {
        return $this->tk_corp
            ->table('tbl_vendor')
            ->get()
            ->getResultArray();
    }
}
