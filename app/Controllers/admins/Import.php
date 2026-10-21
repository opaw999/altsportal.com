<?php

namespace App\Controllers\admins;

use App\Controllers\BaseController;

class Import extends BaseController
{
	public function import_data()
	{
		$file = $this->request->getFile('imported_data');
		if ($file->isValid()) {
			$target_dir = 'import/files/';
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0777, true);
			}

			$name = $file->getClientName();
			$basename = pathinfo($name, PATHINFO_FILENAME);
			$extract_dir = 'import/files/' . $basename . '/';
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0777, true);
			}

			$file->move($target_dir, $name);

			$zipPath = $target_dir . $name;

			if ($this->zip->open($zipPath) === TRUE) {
				$server     = session('server');
				$getServers = getServers($server);
				$pis        = $getServers['pis'];
				$tk         = $getServers['tk'];
				for ($i = 0; $i < $this->zip->numFiles; $i++) {
					$fileName = $this->zip->getNameIndex($i);
					$fn = explode('.', $fileName);
					$v = reset($fn);
					$path = $extract_dir . $fileName;

					$extract = $this->zip->extractTo($extract_dir, $fileName);
					if ($extract) {
						if (
							$fileName == 'termination.sql'
							|| $fileName == 'employee3.sql'
							|| $fileName == 'applicant.sql'
							|| $fileName == 'promo_record.sql'
							|| $fileName == 'promo_vendor_lists.sql'
							|| $fileName == 'locate_promo_company.sql'
						) {
							$sql = "LOAD DATA LOCAL INFILE '$path' REPLACE INTO TABLE $v CHARACTER SET utf8mb4";
							$this->import_model->import_query($pis, $sql);
						} else {
							$sql = "LOAD DATA LOCAL INFILE '$path' REPLACE INTO TABLE $v CHARACTER SET utf8mb4";
							$this->import_model->import_query($tk, $sql);
						}
					}
				}

				$this->zip->close();
				delete_files($target_dir, true);

				echo json_encode(['message' => 'success', 'feedback'  => 'ZIP/RAR file has been imported.']);
			} else {
				echo json_encode(['message' => 'failed to open zip file']);
			}
		}
	}

	public function import_violation_data()
	{
		$file = $this->request->getFile('imported_data');
		if ($file->isValid()) {
			$target_dir = 'import/violation_files/';
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0777, true);
			}

			$name = $file->getClientName();
			$basename = pathinfo($name, PATHINFO_FILENAME);
			$extract_dir = $target_dir . $basename . '/';
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0777, true);
			}

			$file->move($target_dir, $name);

			$zipPath = $target_dir . $name;

			if ($this->zip->open($zipPath) === TRUE) {
				$server     = session('server');
				$getServers = getServers($server);
				$pis        = $getServers['pis'];
				$tk         = $getServers['tk'];
				for ($i = 0; $i < $this->zip->numFiles; $i++) {
					$fileName = $this->zip->getNameIndex($i);
					$fn = explode('.', $fileName);
					$v = reset($fn);
					$path = $extract_dir . $fileName;

					$extract = $this->zip->extractTo($extract_dir, $fileName);
					if ($extract) {
						$sql = "LOAD DATA LOCAL INFILE '$path' REPLACE INTO TABLE $v CHARACTER SET utf8mb4";
						$this->import_model->import_query($tk, $sql);
					}
				}

				$this->zip->close();
				delete_files($target_dir, true);

				echo json_encode(['message' => 'success', 'feedback'  => 'ZIP/RAR file has been imported.']);
			} else {
				echo json_encode(['message' => 'failed to open zip file']);
			}
		}
	}
}
