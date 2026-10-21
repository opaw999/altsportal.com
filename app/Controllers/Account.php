<?php

namespace App\Controllers;

class Account extends BaseController
{
	public function get_form()
	{
		$code = $this->request->getPost('code');

		$userId			= session('userId');
		$supplierId		= session('supplierId');
		$cond			= ['id' => $supplierId];
		$supplierInfo	= $this->supplier_model->getRowArray('tk_corp', 'promo_supplier', $cond);
		$cond			= ['id' => $userId];
		$userInfo		= $this->supplier_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);

		$photo = base_url('assets/img/user/admin.png');
		if ($userInfo['photo']) {
			$photo = base_url($userInfo['photo']);
		}
        
		echo '<input type="hidden" name="code" value="' . $code . '" >';
		if ($code == 'profile') {
			echo '<div class="row">';
			echo '<div class="col-md-4 text-center">';
			echo 	'<a href="javascript:;" onclick="changePhoto(\'' . $photo . '\')">
						<img src="' . $photo . '" class="rounded-circle shadow p-1 border" width="150" height="150">
					</a>
					<h6 class="mt-2">' . $supplierInfo['supplier'] . '</h6>';
			echo '</div>';
			echo '<div class="col-md-6">
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							Address
						</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="address" value="' . $userInfo['address'] . '" placeholder="">
						</div>
					</div>
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							Email Address
						</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="email_address" value="' . $userInfo['email_address'] . '" placeholder="">
						</div>
					</div>
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							Telephone No. <span class="text-danger">&nbsp;</span>
						</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="telephone" value="' . $userInfo['telephone'] . '" placeholder="">
						</div>
					</div>
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							Cellphone No.
						</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="cellphone" value="' . $userInfo['cellphone'] . '" placeholder="">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-end">
							<button type="submit" class="btn btn-primary btn-theme ms-10px">SUBMIT</button>
						</div>
					</div>';
			echo '</div>';
			echo '<div class="col-md-2"></div>';
			echo '</div>';
		} else if ($code == 'username') {
			echo '<div class="row">';
			echo '<div class="col-md-2">';
			echo '</div>';
			echo '<div class="col-md-6">
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							Current Username
						</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="current_username" value="' . $userInfo['username'] . '" placeholder="" readonly required>
						</div>
					</div>
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							New Username
						</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="username" value="" placeholder="Username" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-end">
							<button type="submit" class="btn btn-primary btn-theme ms-10px">SUBMIT</button>
						</div>
					</div>';
			echo '</div>';
			echo '<div class="col-md-2"></div>';
			echo '</div>';
		} else if ($code == 'password') {
			echo '<div class="row">';
			echo '<div class="col-md-2">';
			echo '</div>';
			echo '<div class="col-md-6">
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							Current Password
						</label>
						<div class="col-md-8">
							<input type="password" class="form-control" name="current_password" placeholder="Password" required>
						</div>
					</div>
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							New Password
						</label>
						<div class="col-md-8">
							<input type="password" class="form-control" name="new_password" placeholder="Password" required>
						</div>
					</div>
					<div class="mb-2 row">
						<label class="col-form-label col-md-4 text-lg-end">
							Confirm Password
						</label>
						<div class="col-md-8">
							<input type="password" class="form-control" name="password" placeholder="Password" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-end">
							<button type="submit" class="btn btn-primary btn-theme ms-10px">SUBMIT</button>
						</div>
					</div>';
			echo '</div>';
			echo '<div class="col-md-2"></div>';
			echo '</div>';
		}
	}

	public function account_update()
	{
		$input	= $this->request->getPost();
		$userId	= session('userId');

		if ($input['code'] == 'profile') {
			$data = [
				'address' 		=> $input['address'],
				'email_address'	=> $input['email_address'],
				'telephone' 	=> $input['telephone'],
				'cellphone' 	=> $input['cellphone'],
				'updated_at' 	=> $this->timestamp
			];
			$cond = ['id' => $userId];
			$update = $this->supplier_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);
			if ($update) {
				echo json_encode(['message' => 'success', 'feedback' => 'Account details has been updated.', 'code' => $input['code']]);
			} else {
				echo json_encode(['message' => 'failed']);
			}
		} else if ($input['code'] == 'username') {
			$cond = ['username' => $input['username'], 'id !=' => $userId];
			$check = $this->supplier_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);
			if ($check) {
				echo json_encode(['message' => 'Username already exist.']);
			} else {
				$data = [
					'username'		=> $input['username'],
					'updated_at'	=> $this->timestamp
				];

				$cond = ['id' => $userId];
				$update = $this->supplier_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);
				if ($update) {
					session()->destroy();
					echo json_encode(['message' => 'success', 'feedback' => 'Account username has been updated. The system will logout and kindly login again.', 'code' => $input['code']]);
				} else {
					echo json_encode(['message' => 'failed']);
				}
			}
		} else if ($input['code'] == 'password') {
			$cond = ['id' => $userId];
			$check = $this->supplier_model->getRowArray('tk_corp', 'promo_supplier_user', $cond);
			if ($check) {
				if (password_verify($input['current_password'], $check['password'])) {
					if (password_verify($input['new_password'], $input['password'])) {
						$password = password_hash($input['password'], PASSWORD_DEFAULT);
						$data = [
							'password'		=> $password,
							'updated_at'	=> $this->timestamp
						];

						$update = $this->supplier_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);
						if ($update) {
							session()->destroy();
							echo json_encode(['message' => 'success', 'feedback' => 'Account password has been updated. The system will logout and kindly login again.', 'code' => $input['code']]);
						} else {
							echo json_encode(['message' => 'failed']);
						}
					} else {
						echo json_encode(['message' => 'New password dont match to the confirmed password.']);
					}
				} else {
					echo json_encode(['message' => 'Current password dont match.']);
				}
			}
		}
	}

	public function change_photo()
	{
		$userId	= session('userId');
		$file 	= $this->request->getFile('file');
		if ($file->isValid()) {
			$ext = pathinfo($file->getClientName(), PATHINFO_EXTENSION);
			$path = FCPATH . 'assets/img/user';
			$name = $userId . '_' . date('YmdHis') . '.' . $ext;
			if ($file->move($path, $name)) {
				$data = array(
					'photo'    		=> 'assets/img/user/' . $name,
					'updated_at'	=> $this->timestamp
				);

				$cond = ['id' => $userId];
				$update = $this->supplier_model->updateData('tk_corp', 'promo_supplier_user', $cond, $data);
				if ($update) {
					echo json_encode(['message' => 'success', 'feedback' => 'Profile photo has been updated.']);
				} else {
					echo json_encode(['message' => 'failed']);
				}
			}
		}
	}
}
