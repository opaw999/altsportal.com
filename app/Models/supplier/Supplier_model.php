<?php

namespace App\Models\supplier;

use App\Models\BaseModel;

class Supplier_model extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    // public function supplier_details($user_id)
    // {
    //     $query = $this->tk_corp->table('promo_supplier')
    //         // ->select('promo_supplier.supplier')
    //         ->join('promo_supplier_user', 'promo_supplier_user.supplier_id = promo_supplier.id')
    //         ->where('promo_supplier_user.supplier_id', $user_id)
    //         ->get();

    //     $row = $query->getRow();
    //     return $row->supplier ?? '';
    // }

    // public function supplier_details($user_id)
    // {
    // $query = $this->db->select('supplier, photo')
    //     ->from('promo_supplier_user')
    //     ->join('promo_supplier', 'promo_supplier.id = promo_supplier_user.supplier_id')
    //     ->where('promo_supplier_user.id', $user_id)
    //     ->get();
    // return $query->row();
    // }

    //     public function penalty_details($vendor_code, $store, $category_id, $date_from, $date_to) {

    //         $array = array('vendor_id' => $vendor_code, 'store' => $store, 'category_id' => $category_id, 'date_from' => $date_from, 'date_to' => $date_to);
    //         $query = $this->timekeeping2->from('concessionaires_penalties')
    //                         ->where($array)
    //                         ->get();
    //         return $query->row();
    //     }

    //     public function penalty_perday($id)
    //     {
    //         $query = $this->timekeeping2->get_where('concessionaires_penalty_perdays', array('penalty_id' => $id));
    //         return $query->result();
    //     }

    //     public function supplier_name($vendor_code)
    //     {
    //         $query = $this->pis2->get_where('promo_vendor_lists', array('vendor_code' => $vendor_code));
    //         return $query->row();
    //     }

    //     public function business_unit($store)
    //     {
    //         $query = $this->pis2->get_where('locate_promo_business_unit', array('bunit_field' => $store));
    //         return $query->row();
    //     }

    //     public function category($category_id)
    //     {
    //         $query = $this->timekeeping2->get_where('vendor_categories', array('id' => $category_id));
    //         return $query->row();
    //     }
}
