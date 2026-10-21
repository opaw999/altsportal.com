<?php

namespace App\Controllers;

class Message extends BaseController
{
    public function view_message()
    {

        $id = session('supplierId');
        $query = $this->message_model->chat_messages($id);
        if ($query) {

            foreach ($query as $key => $row) {

                $datetime = date('m/d/Y h:i A', strtotime($row['date_submitted']));
                $day = date('Y-m-d', strtotime($row['date_submitted']));
                if ($day == $this->today) {

                    $datetime = date('h:i A', strtotime($row['date_submitted']));
                }

                if ($row['userId'] == $id) {
                    echo    '<div class="msg user">
                                <span>' . $row['message'] . '</span>
                                <span class="chat_date_time">' . $datetime . '</span>
                            </div>';
                } else {

                    $status = '';
                    if (!$row['supplier_read']) {
                        $status = '<i class="fa fa-circle text-warning me-1"></i>';
                    }

                    $cond   = ['user_no' => $row['userId']];
                    $admin  = $this->supplier_model->getRowArray('tk_corp', 'admin_user', $cond)['username'] ?? '';

                    echo    '<div class="msg admin">
                                <span>' . $status . $row['message'] . '</span>
                                <span class="chat_date_time">' . $admin . ' ' . $datetime . '</span>
                            </div>';
                }
            }
        } else {
            echo    '<div class="msg bot">
                        <span>You have no messages...</span>
                        <span class="chat_date_time">' . date('h:i A') . '</span>
                    </div>';
        }
    }

    public function send_message()
    {
        $input      = $this->request->getPost();
        $userId     = session('supplierId');
        $cond       = ['id' => $userId];
        $supplier   = $this->supplier_model->getRowArray('tk_corp', 'promo_supplier', $cond);

        $cond   = ['id' => session('userId')];
        $photo  = $this->supplier_model->getRowArray('tk_corp', 'promo_supplier_user', $cond)['photo'] ?? 'assets/img/user/admin.png';

        $data = array(
            'userId'            => $userId,
            'sender'            => $supplier['supplier'],
            'subject'           => 'concern',
            'message'           => $input['message'],
            'supplier_read'     => 1,
            'date_submitted'    => $this->timestamp
        );

        $send = $this->message_model->insertData('tk_corp', 'message', $data);
        if ($send) {

            $unread = $this->email_model->count_unread_msg();
            $payload = [
                'unread'    => $unread,
                'reciever'  => 'admin',
                'cc'        => $userId,
                'sender'    => $supplier['supplier'],
                'message'   => $input['message'],
                'photo'     => $photo,
                'time'      => date('h:i A', strtotime($this->timestamp))
            ];

            $this->pusher->broadcast('promo-chat', 'new-message', $payload);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function read_all_messages()
    {
        $userId = session('supplierId');
        $cond = "userId='$userId' OR cc='$userId'";
        $data = ['supplier_read' => 1,];
        $read = $this->message_model->updateData('tk_corp', 'message', $cond, $data);
    }
}
