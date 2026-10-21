<?php

namespace App\Models;

use App\Models\BaseModel;

class Login_model extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkSupplierAccount($username)
    {
        $query = $this->tk_corp->table('promo_supplier_user')
            ->where(['username' => $username])
            ->get()
            ->getRowArray();

        return $query ?? false;
    }

    public function supplierUserInfo($userId)
    {
        $query = $this->tk_corp->table('promo_supplier_user')
            ->where(['id' => $userId])
            ->get()
            ->getRowArray();

        return $query ?? false;
    }

    public function checkAdminAccount($username)
    {
        $query = $this->tk_corp->table('admin_user')
            ->where(['username' => $username, 'status' => 'active'])
            ->get()
            ->getRowArray();

        return $query ?? false;
    }

    public function adminUserInfo($user_no)
    {
        $query = $this->tk_corp->table('admin_user')
            ->where(['user_no' => $user_no])
            ->get()
            ->getRowArray();

        return $query ?? false;
    }
}
