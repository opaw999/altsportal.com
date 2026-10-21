<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Admin extends BaseController
{
	public function submit_username()
	{
		$input = $this->request->getPost();
		$c_username = $input['current_username'];
		$n_username = $input['new_username'];
		$r_username = $input['retype_username'];

		$cond = ['user_no' => session('adminId'), 'username' => $c_username];
		$check = $this->admin_model->getRowArray('tk_corp', 'admin_user', $cond);
		if ($check) {

			$cond = ['user_no !=' => session('adminId'), 'username' => $n_username];
			$exist = $this->admin_model->getRowArray('tk_corp', 'admin_user', $cond);
			if ($exist) {
				die('New Username already exist.');
			} else {

				$cond = ['user_no' => session('adminId')];
				$data = ['username' => $n_username];
				$update = $this->admin_model->updateData('tk_corp', 'admin_user', $cond, $data);
				if ($update) {
					echo json_encode(['message' => 'success', 'feedback' => 'Username has been updated. The system will logout and kindly login again.']);
				} else {
					die('Failed to update username.');
				}
			}
		} else {

			die('Current Username do not match.');
		}
	}

	public function submit_password()
	{
		$input = $this->request->getPost();
		$c_password = $input['current_password'];
		$n_password = $input['new_password'];
		$r_password = $input['retype_password'];

		$cond = ['user_no' => session('adminId')];
		$check = $this->admin_model->getRowArray('tk_corp', 'admin_user', $cond);
		if ($check) {
			if (password_verify($c_password, $check['password'])) {

				$password = password_hash($n_password, PASSWORD_DEFAULT);
				$cond = ['user_no' => session('adminId')];
				$data = ['password' => $password];
				$update = $this->admin_model->updateData('tk_corp', 'admin_user', $cond, $data);
				if ($update) {
					echo json_encode(['message' => 'success', 'feedback' => 'Password has been updated. The system will logout and kindly login again.']);
				} else {
					die('Failed to update password.');
				}
			} else {

				die('Current Password do not match.');
			}
		} else {

			die('User dont exists.');
		}
	}
}
