<?php

namespace App\Models\admins;

use App\Models\BaseModel;

class Email_model extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function supplier_ids()
    {
        $query = $this->tk_corp->table('message')
            ->select('userId')
            ->distinct()
            ->where('cc', 0)
            ->get()
            ->getResultArray();

        return $query;
    }

    public function supplier_chat_messages()
    {
        $messages = [];
        $userIds = $this->supplier_ids();
        foreach ($userIds as $id) {
            $query = $this->tk_corp->table('message')
                ->groupStart()
                ->where('userId', $id['userId'])
                ->orWhere('cc', $id['userId'])
                ->groupEnd()
                ->orderBy('msg_id', 'DESC')
                ->get();
            $messages[] = $query->getRowArray();
        }

        $ids = array_column($messages, 'msg_id');
        array_multisort($ids, SORT_DESC);

        return $ids;
    }

    public function count_unread_msg()
    {
        $query = $this->tk_corp->table('message')
            ->where('admin_read', 0)
            ->get();

        return $query->getNumRows();
    }

    public function admin_read_message($user_id)
    {
        $data = ['admin_read' => 1];
        $query = $this->tk_corp->table('message')
            ->where('userId', $user_id)
            ->update($data);
        return $query;
    }

    public function message_sender($user_id)
    {
        $query = $this->tk_corp->table('promo_supplier')
            ->select('promo_supplier.supplier')
            ->join('promo_supplier_user', 'promo_supplier_user.supplier_id = promo_supplier.id')
            ->where('promo_supplier_user.supplier_id', $user_id)
            ->get();

        $row = $query->getRow();
        return $row->supplier ?? '';
    }
}
