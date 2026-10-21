<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Email extends BaseController
{
    public function supplier_chat_messages()
    {
        $list = '';
        $email = $this->email_model->supplier_chat_messages();
        foreach ($email as $id) {
            $msg = $this->email_model->getRowArray('tk_corp', 'message', ['msg_id' => $id]);
            $userId = $msg['userId'];

            if (strlen($msg['sender']) > 28) {
                $supplierName = substr($msg['sender'], 0, 28) . "...";
            } else {
                $supplierName = $msg['sender'];
            }

            if (date('Y-m-d', strtotime($msg['date_submitted'])) == date('Y-m-d')) {
                $datetime = date('h:i A', strtotime($msg['date_submitted']));
            } else {
                $datetime = date('m/d/y', strtotime($msg['date_submitted']));
            }

            $feedback = '';
            if ($msg['cc'] != 0) {
                $userId = $msg['cc'];
                $feedback = 'Admin: ';
            }

            $read = false;
            if ($msg['admin_read'] == 1) {
                $read = true;
                if (strlen($msg['message']) > 80) {
                    $message = $feedback . '' . substr($msg['message'], 0, 80) . "...";
                } else {
                    $message = $feedback . '' . $msg['message'];
                }
            } else {

                if (strlen($msg['message']) > 80) {
                    $mess = $feedback . '' . substr($msg['message'], 0, 80);
                } else {
                    $mess = $feedback . '' . $msg['message'];
                }
                $message = '<b><i class="fa fa-circle text-warning"></i> ' . $mess . '</b>';
            }
            $cond = ['supplier_id' => $userId];
            $photo = $this->email_model->getRowArray('tk_corp', 'promo_supplier_user', $cond)['photo'] ?? 'assets/img/user/admin.png';

            $cond = ['user_id' => $userId, 'admin_id' => session('adminId')];
            $chk = $this->email_model->getRowArray('tk_corp', 'promo_active_messages', $cond);
            $active = ($chk) ? 'active' : '';

            $data = '<div id="' . $userId . '" class="messenger-chat-item chat_list ' . $active . '">
                        <a href="javascript:;" class="messenger-chat-link" onclick="chat_list(\'' . $userId . '\',\'' . $read . '\')" data-toggle-class="messenger-chat-content-mobile-toggled" data-target="#messenger">
                            <div class="messenger-chat-media"><img alt="" src="' . base_url($photo) . '" /></div>
                            <div class="messenger-chat-content">
                                <div class="messenger-chat-title">
                                    <div>' . $supplierName . '</div>
                                    <div class="messenger-chat-time">' . $datetime . '</div>
                                </div>
                                <div class="messenger-chat-desc">' . $message . '</div>
                            </div>
                        </a>
                    </div>';
            $list .= $data;
        };
        echo json_encode($list);
    }

    public function view_supplier_messages()
    {
        $id = $this->request->getPost('id');
        $msg = '<input type="hidden" name="cc" value="' . $id . '">';
        $cond = "userId='$id' OR cc='$id' ORDER BY msg_id ASC";
        $messages = $this->email_model->getResultArray('tk_corp', 'message', $cond);
        foreach ($messages as $key => $row) {

            $cond = ['supplier_id' => $row['userId']];
            $photo = $this->email_model->getRowArray('tk_corp', 'promo_supplier_user', $cond)['photo'] ?? 'assets/img/user/admin.png';

            if (date('Y-m-d', strtotime($row['date_submitted'])) == date('Y-m-d')) {
                $datetime = date('h:i A', strtotime($row['date_submitted']));
            } else {
                $datetime = date('m/d/y h:i A', strtotime($row['date_submitted']));
            }

            if (strlen($row['sender']) > 40) {
                $senders = substr($row['sender'], 0, 40) . "...";
            } else {
                $senders = $row['sender'];
            }

            $loc = ($row['userId'] == $id) ? 'start' : 'end';
            $nameHtml = ($row['userId'] == $id) ? '<div class="widget-chat-name text-blue">' . $senders . '</div>' : '';
            $msg .= '<div class="widget-chat-item with-media ' . $loc . '">
                        <div class="widget-chat-media">
                            <img alt="" src="' . base_url($photo) . '" />
                        </div>
                        <div class="widget-chat-info">
                            <div class="widget-chat-info-container">
                                ' . $nameHtml . '
                                <div class="widget-chat-message">' . $row['message'] . '</div>
                                <div class="widget-chat-time">' . $datetime . '</div>
                            </div>
                        </div>
                    </div>';
        }

        $unread = $this->email_model->count_unread_msg();
        $sender = $this->email_model->getRowArray('tk_corp', 'message', ['userId' => $id])['sender'];
        echo json_encode(['sender' => $sender, 'msg' => $msg, 'unreadMail' => $unread]);
    }

    public function admin_read_message()
    {
        $id = $this->request->getPost('id');
        $read = $this->email_model->admin_read_message($id);
        if ($read) {
            $unread = $this->email_model->count_unread_msg();
            echo json_encode(['unreadMail' => $unread]);
        }
    }

    public function send_feedback()
    {
        $input = $this->request->getPost();
        $sender = $this->email_model->message_sender($input['cc']);
        $data = array(
            'userId'            => session('adminId'),
            'cc'                => $input['cc'],
            'sender'            => $sender,
            'message'           => htmlspecialchars($input['feedback']),
            'admin_read'        => 1,
            'date_submitted'    => $this->timestamp
        );

        $send = $this->email_model->insertData('tk_corp', 'message', $data);
        if ($send) {
            $unread = $this->message_model->count_unread_msg($input['cc']);
            $payload = [
                'supplier_unread' => $unread,
                'reciever'  => 'supplier',
                'cc'        => $input['cc'],
                'sender'    => session('admin_username'),
                'message'   => $input['feedback'],
                'time'      => date('h:i A')
            ];

            $push = $this->pusher->broadcast('promo-chat', 'new-message', $payload);
            if ($push) {
                return $this->response->setJSON(['status' => 'success']);
            }
        }
    }

    public function update_active_chat()
    {
        $id = $this->request->getPost('id');
        $cond = ['admin_id' => session('adminId')];
        $active = $this->email_model->getRowArray('tk_corp', 'promo_active_messages', $cond);
        if ($active) {
            $data = ['user_id' => $id, 'updated_at' => $this->timestamp];
            return $this->email_model->updateData('tk_corp', 'promo_active_messages', $cond, $data);
        } else {
            $data = array('user_id' => $id, 'created_at' => $this->timestamp, 'admin_id' => session('adminId'),);
            return $this->email_model->insertData('tk_corp', 'promo_active_messages', $data);
        }
    }

    public function newMessageForm() {}
}
