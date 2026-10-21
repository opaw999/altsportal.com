<?php

namespace App\Controllers;

class Contact extends BaseController
{
	public function submit_message()
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
			'subject'           => $input['subject'],
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

			$sent = $this->pusher->broadcast('promo-chat', 'new-message', $payload);
			echo json_encode(['message' => 'success', 'feedback' => 'Your message has been sent.']);
		} else {
			echo json_encode(['message' => 'failed']);
		}
	}
}
