<?php

namespace App\Models\supplier;

use App\Models\BaseModel;

class Message_model extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function count_unread_msg($supplierId)
    {
        $query = $this->tk_corp->table('message')
            ->groupStart()
            ->where('userId', $supplierId)
            ->orWhere('cc', $supplierId)
            ->groupEnd()
            ->where('supplier_read', 0)
            ->get();

        return $query->getNumRows();
    }

    public function chat_messages($supplierId)
    {
        $query = $this->tk_corp->table('message')
            ->groupStart()
            ->where('userId', $supplierId)
            ->orWhere('cc', $supplierId)
            ->groupEnd()
            ->get();

        return $query->getResultArray();
    }
}
