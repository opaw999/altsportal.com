<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Logout extends BaseController
{
	public function logout()
	{
		session()->destroy();
		return redirect()->to('admins/login');
	}
}
