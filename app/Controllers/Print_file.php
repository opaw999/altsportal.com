<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Print_file extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('dtr_model');
		$this->load->model('violation_model');
	}

	public function print_dtr()
	{

		$fetch = $this->input->get(NULL, TRUE);
		if ($fetch['server'] == "colonnade") {

			$pis = "pis_colonnade";
			$tk  = "tk_colonnade";
		} else {

			$pis = "pis";

			if ($fetch['server'] == "talibon") {

				$tk = "tk_talibon";
			} else if ($fetch['server'] == "tubigon") {

				$tk = "tk_tubigon";
			} else {

				$tk = "timekeeping";
			}
		}

		// create new PDF document
		$this->load->library('Pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Zoren  Ormido');
		$pdf->SetTitle('Promo DTR');
		$pdf->SetSubject('DTR Entry');
		$pdf->SetKeywords('DTR, promodiser, merchandiser, diser, promo');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(12, 12, 12);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 15);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('times', '', 10);

		// add a page
		$pdf->AddPage();

		$data 	= $fetch['chkEmpId'];
		$dF 	= $fetch['dateFrom'];
		$dT 	= $fetch['dateTo'];
		$cut 	= $fetch['cut'];
		$cutoff = $fetch['cutoff'];

		$datetime1 = date_create($dF);
		$datetime2 = date_create($dT);

		$interval = date_diff($datetime1, $datetime2);
		$days   = $interval->format("%d") + 1;

		// set margins
		$pdf->SetMargins(12, 12, 12);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		for ($i = 0; $i < count($data); $i++) {

			$id = $data[$i];

			if ($cut == 1) {
				$cutOff = "1stcutoff";
			} else {
				$cutOff = "2ndcutoff";
			}

			$con = "SELECT * FROM $cutOff WHERE dateFrom = '" . $dF . "' AND dateTo = '" . $dT . "' AND 
									biometricId = '" . $id . "' and statCut = '" . $cutoff . "'";
			$result = $this->dtr_model->return_data_result_array($tk, $con);
			foreach ($result as $row) {

				$bioM 			= $row['bioMetricId'];
				// $dw 			= $row['daysWork'];
				$dw            = round($row['daysWork'], 3);
				$totalOvertime 	= $row['otForPayment'];
				$legalHoliday 	= $row['legHol'];
				$specialHoliday = $row['speHol'];

				if (!empty($totalOvertime)) {
					$totalOvertime = "$totalOvertime";
				} else {
					$totalOvertime = "0";
				}


				$lenId = strlen($bioM);
				if ($lenId == 11) {
					$fieldId = "bioMetricId";
					$allowId = "Biometric ID";
				}
				if ($lenId == 13) {
					$fieldId = "barcodeId";
					$allowId = "Logbox ID";
				}

				// empId
				$assign = "SELECT empId FROM assign WHERE assign.$fieldId = '" . $bioM . "' AND empId NOT LIKE '%\_%'";
				$empId = $this->dtr_model->return_data_row_array($tk, $assign)['empId'];

				// employee info
				// $info = "SELECT name, promo_company, promo_department, promo_type
				// 			FROM employee3 
				// 			INNER JOIN promo_record ON employee3.record_no = promo_record.record_no
				// 			WHERE employee3.emp_id = '".$empId."' ORDER BY employee3.record_no DESC";

				$info = "SELECT name, promo_company, promo_department, promo_type
					FROM employee3, promo_record
					WHERE
					employee3.record_no = promo_record.record_no AND employee3.emp_id = promo_record.emp_id AND
					employee3.emp_id = '" . $empId . "' ORDER BY employee3.record_no DESC LIMIT 1";

				$ass = $this->dtr_model->return_data_row_array($pis, $info);

				$name = $ass['name'];
				$promo_company = $ass['promo_company'];
				$promo_department = $ass['promo_department'];
				$promo_type = $ass['promo_type'];

				// Service Incentive Leave 
				$sil = 0;

				$fetch = [
					'empId' => $empId,
					'dateFrom' => $dF,
					'dateTo' => $dT
				];

				//current
				$sil_cu = $this->dtr_model->no_sil_current($tk, $fetch)['sil'];

				//previous
				$sil_prev = $this->dtr_model->no_sil_previous($tk, $fetch)['sil'];

				$sil       = $sil_prev + $sil_cu;

				$pdf->SetFont('times', 'B', 10, 'L');
				$pdf->Cell(180, 5, 'Integrated Daily Time Record for Promo & Merchandiser', 0, 1, 'C');
				$pdf->Cell(180, 5, 'For the Period of ' . date("M. d, Y", strtotime($dF)) . ' to ' . date("M. d, Y", strtotime($dT)), 0, 1, 'C');

				$pdf->SetFont('times', 'B', 9, 'L');
				$pdf->Ln(1);
				$pdf->Cell(20, 5, 'Name', 0, 0, 'L');
				$pdf->Cell(120, 5, ': ' . ucwords(strtolower($name)), 0, 0, 'L');
				$pdf->SetX(120);
				$pdf->Cell(0, 5, "Days Worked");
				$pdf->SetX(144);
				$pdf->Cell(0, 5, ": $dw");
				$pdf->SetX(165);
				$pdf->Cell(0, 5, "Legal Holiday");
				$pdf->SetX(190);
				$pdf->Cell(0, 5, ": $legalHoliday");

				$pdf->Ln(5);
				$pdf->Cell(20, 5, 'Company', 0, 0, 'L');
				$y1 = $pdf->GetY();

				$pdf->MultiCell(80, 5, ": $promo_company", 0, 'L');
				$y2 = $pdf->GetY();

				$pdf->SetY($y1);

				$pdf->SetX(120);

				$pdf->Cell(0, 5, "Overtime");
				$pdf->SetX(144);
				$pdf->Cell(0, 5, ": $totalOvertime");
				$pdf->SetX(165);
				$pdf->Cell(0, 5, "SIL");
				$pdf->SetX(190);
				$pdf->Cell(0, 5, ": $sil");
				$pdf->Ln(5);
				$pdf->SetX(120);
				$pdf->Cell(0, 5, "Special Holiday");
				$pdf->SetX(144);
				$pdf->Cell(0, 5, ": $specialHoliday");


				$pdf->SetY($y2) - 5;
				// $pdf->Ln(7);
				$pdf->Cell(20, 5, 'Department', 0, 0, 'L');
				$pdf->Cell(120, 5, ": $promo_department", 0, 0, 'L');

				$pdf->Ln(2);

				$pdf->Cell(20, 5, '____________________________________________________________________________________________________________________', 0, 0, '');
				$pdf->Ln(5);

				$pdf->Cell(22, 3, 'Date', 0, 0, 'L');
				$pdf->Cell(11, 3, 'Day', 0, 0, 'L');
				$pdf->Cell(20, 3, 'IN1', 0, 0, 'L');
				$pdf->Cell(20, 3, 'OUT1', 0, 0, 'L');
				$pdf->Cell(20, 3, 'IN2', 0, 0, 'L');
				$pdf->Cell(20, 3, 'OUT2', 0, 0, 'L');
				$pdf->Cell(20, 3, 'IN3', 0, 0, 'L');
				$pdf->Cell(20, 3, 'OUT3', 0, 0, 'L');
				$pdf->Cell(12, 3, 'HW', 0, 0, 'L');
				$pdf->Cell(12, 3, 'DW', 0, 0, 'L');
				$pdf->Cell(12, 3, 'OT', 0, 0, 'L');

				$pdf->Ln(2);
				$pdf->Cell(20, 5, '____________________________________________________________________________________________________________________', 0, 0, '');

				$pdf->Ln(6);
				$pdf->SetFont('times', '', 10, 'L');

				$dateFrom 	= $fetch['dateFrom'];
				$dateTo 	= $fetch['dateTo'];

				while (strtotime($dateFrom) <= strtotime($dateTo)) {

					$date = date("m/d/Y", strtotime($dateFrom));
					$day = date("D", strtotime($dateFrom));

					$dutyId = '';
					$in1_store = $out1_store = $in2_store = $out2_store	= $in3_store = $out3_store = "";
					$in1 = $out1 = $in2 = $out2 = $in3 = $out3 = "";

					$tempDate = date('Y-m-d', strtotime($dateFrom));
					$sql = "SELECT dutyId, duty.date, in1, out1, in2, out2, in3, out3 
										 FROM duty
										 WHERE bioMetricId = '" . $bioM . "' AND date = '" . $tempDate . "' ORDER BY dutyId DESC";
					$dtr = $this->dtr_model->return_data_row_array($tk, $sql);

					$dutyId = $dtr['dutyId'];
					$in1 	= $dtr['in1'];
					$out1 	= $dtr['out1'];

					$in2 	= $dtr['in2'];
					$out2 	= $dtr['out2'];

					$in3 	= $dtr['in3'];
					$out3 	= $dtr['out3'];

					// store entry
					$in1_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateFrom, $in1, "I");
					$out1_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateFrom, $out1, "O");
					$in2_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateFrom, $in2, "I");
					$out2_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateFrom, $out2, "O");
					$in3_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateFrom, $in3, "I");
					$out3_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateFrom, $out3, "O");

					if (empty($out3_store)) {
						$out3_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($dateFrom . " +1 day")), $out3, "O");
					}

					if (!empty($in1)) {
						$in1 = date('h:i A', strtotime($in1));
					}

					if (!empty($out1)) {
						$out1 = date('h:i A', strtotime($out1));
					}


					if (!empty($in2)) {
						$in2 = date('h:i A', strtotime($in2));
					}

					if (!empty($out2)) {
						$out2 = date('h:i A', strtotime($out2));
					}

					if (!empty($in3)) {
						$in3 = date('h:i A', strtotime($in3));
					}

					if (!empty($out3)) {

						$out3 = date('h:i A', strtotime($out3));
					}

					$sql_1 = "SELECT * FROM duty_logbox WHERE bioMetricId = '$bioM' AND dateDuty = '$tempDate'";
					$dutyl  =  $this->dtr_model->return_data_row_array($tk, $sql_1);

					$hoursWork = 0;
					$oT = 0;

					$hh = "SELECT hoursWork FROM perday WHERE dutyId = '" . $dutyId . "'";
					$perday = $this->dtr_model->return_data_row_array($tk, $hh);
					$phw = $perday['hoursWork'];
					$res = explode("&", $phw);

					if ($res[0] == "") {
						$res[0] = 0;
					}

					$h = floatval($res[0]) * 1;
					$m = @floatval($res[1]) * 1;

					// days worked
					$tempH = $h / 8;
					$tempM = ($m / 60) / 8;
					$tempM2 = ($m / 60);

					$tempHM = $tempH + $tempM;
					$newHM = round($tempHM, 3);

					// hours worked
					$min = round($tempM2, 3);
					$hw = $h + $min;

					// overtime
					$query = "SELECT otApp FROM otforpayment WHERE emp_id = '" . $empId . "' AND otDate = '" . $tempDate . "' ORDER BY pay_id DESC LIMIT 1";
					$qt = $this->dtr_model->return_data_row_array($tk, $query);
					// echo $qt['otApp'];
					$oT = floatval($qt['otApp']) * 1;
					if (empty($oT) || $oT == 0) {
						$oT = 0;
					}


					$dayB = date('l', strtotime($dateFrom));
					// check if holiday	

					$chkh 	= "SELECT nameHoliday, category FROM holidays_promo WHERE 
											 date = '" . $tempDate . "' AND moveDate = '' LIMIT 1";
					$ch   	= $this->dtr_model->return_data_row_array($tk, $chkh);
					$numH1  = $this->dtr_model->return_data_num_rows($tk, $chkh);
					$nameH1 = $ch['nameHoliday'];
					$cat1  	= $ch['category'];

					$chkh 	= "SELECT nameHoliday, category FROM holidays_promo WHERE 
											   moveDate = '" . $tempDate . "' LIMIT 1";
					$ch2   	= $this->dtr_model->return_data_row_array($tk, $chkh);
					$numH2  = $this->dtr_model->return_data_num_rows($tk, $chkh);
					$nameH2 = $ch2['nameHoliday'];
					$cat2  	= $ch2['category'];

					if ($numH1 != 0) {
						$nH 	= "$nameH1";
						$cat 	= "$cat1";
					}
					if ($numH2 != 0) {
						$nH 	= "$nameH2";
						$cat 	= "$cat2";
					}
					// end of check if holiday	

					// check if na assignan	

					$yesHol = "";
					$numODA = 0;
					if ($numH1 != 0 || $numH2 != 0) {
						$yesHol = "true";

						$query_chkdateo = "SELECT amsId FROM assign_mul_sc WHERE 
													     empId = '$empId' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo";
						$chkdateo = $this->dtr_model->return_data_result_array($tk, $query_chkdateo);
						foreach ($chkdateo as $cht) {

							$amsId 	= $cht['amsId'];

							$consh  = "SELECT count(request_odaf.amsId) as numODA FROM request_odaf WHERE 
														  request_odaf.amsId = '" . $amsId . "'";
							$numODA = $numODA + $this->dtr_model->return_data_row_array($tk, $consh)['numODA'];
						}
					}
					// end of check if na assignan	

					$countDO = 0;
					$count   = 0;
					$show_do = 0;
					// check if na assignan sa day off	
					$sql_do = "SELECT amsId FROM assign_mul_sc WHERE empId = '" . $empId . "' AND
												status = 'active' AND status2 = 'Approved' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo 
												ORDER BY id DESC";
					$queryMSC 	= $this->dtr_model->return_data_row_array($tk, $sql_do);

					if (is_array($queryMSC) && count($queryMSC)) {

						$amsId = $queryMSC['amsId'];

						//ge assignan ug dayoff pag CS
						$sq_do = "SELECT count(ass_mul_do.empId) as numDO FROM ass_mul_do WHERE 
												  	ass_mul_do.amsId = '" . $amsId . "' AND ass_mul_do.empId = '" . $empId . "' AND ass_mul_do.date = '" . $tempDate . "'";
						$count = $this->dtr_model->return_data_row_array($tk, $sq_do)['numDO'];

						//short term nga day-off request
						$sq_dost  = "SELECT count(empId) as numDOST FROM ass_change_do WHERE
													ass_change_do.empId = '" . $empId . "' AND ass_change_do.dateToChange = '" . $tempDate . "' AND status = 'Approved'";
						$queryCDO = $this->dtr_model->return_data_row_array($tk, $sq_dost)['numDOST'];

						//ge assignan ug new day-off pero walay change day-off nga request
						if ($count && $queryCDO == 0) {
							$show_do = 1;
						}

						//na assignan ug new day-off sa supv pero nag request ug change day-off ang emp. vice versa
						$sq_new_dost = "SELECT count(empId) as numNDOST FROM ass_change_do WHERE
													ass_change_do.empId = '" . $empId . "' AND ass_change_do.dateNew = '" . $tempDate . "' AND status = 'Approved'";
						$queryCDO = $this->dtr_model->return_data_row_array($tk, $sq_new_dost)['numNDOST'];
						if ($queryCDO) {
							// echo $queryCDO."".$tempDate."<br>";

							// ge change day-off pag human ug cs sa supv
							$sql = "SELECT tb1.time, tb1.date, tb2.dateToChange FROM approveby as tb1 INNER JOIN ass_change_do as tb2 ON tb2.acdId=tb1.requestId WHERE
											tb2.empId = '" . $empId . "' AND tb2.dateNew = '" . $tempDate . "' AND tb1.requestType = 'CDOS' AND tb2.status = 'Approved'";
							$query = $this->dtr_model->return_data_row_array($tk, $sql);
							if (is_array($query) && count($query)) {

								$dateApp        = $query['date'];
								$timeApp        = $query['time'];
								$dateToChange   = $query['dateToChange'];

								//date & time sa pag approved sa supvr
								$app_details    = date("Y-m-d H:i:s", strtotime("$dateApp $timeApp"));

								$sql_do_cs = "SELECT dateReq, timeReq FROM ass_mul_do as tb1 INNER JOIN request_new_shift as tb2 ON tb2.amsId=tb1.amsId WHERE
								    				tb1.empId = '" . $empId . "' AND tb1.date = '" . $dateToChange . "' LIMIT 1";
								$query = $this->dtr_model->return_data_row_array($tk, $sql_do_cs);
								if (count($query)) {
									$dateReq = $query['dateReq'];
									$timeReq = $query['timeReq'];

									$req_details    = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
									if ($app_details > $req_details) {
										$show_do = 1;
									}
								}
							}
						}
					}

					//wala na assign ug new day-off sa change shift pero nag request ug change day-off
					$old_do = 0;
					$req_do = 0;

					if (is_array($queryMSC) && count($queryMSC) == 0) {
						//petsa nga bag-o ang day-off
						$sq_new_dost = "SELECT count(empId) as numNDOST FROM ass_change_do WHERE
													ass_change_do.empId = '" . $empId . "' AND ass_change_do.dateNew = '" . $tempDate . "' AND status = 'Approved'";
						$queryCDO = $this->dtr_model->return_data_row_array($tk, $sq_new_dost)['numNDOST'];

						if ($queryCDO) {
							$req_do  = 1;
							$show_do = 1;
						}

						//petsa nga ang day-off ilisdan ug dili e display
						$sq_dost  = "SELECT count(empId) as numDOST FROM ass_change_do WHERE
													ass_change_do.empId = '" . $empId . "' AND ass_change_do.dateToChange = '" . $tempDate . "' AND status = 'Approved'";
						$queryCDO = $this->dtr_model->return_data_row_array($tk, $sq_dost)['numDOST'];
						if ($queryCDO) {
							$old_do = 1;
						}
					}

					// default day-off
					if (is_array($queryMSC) && count($queryMSC) == 0 && $show_do == 0 && $old_do == 0) {

						$day2 = date('l', strtotime($tempDate));

						$sql = "SELECT count(empId) as numNDO FROM assign_day_off WHERE 
										assign_day_off.empId = '" . $empId . "' AND assign_day_off.day = '" . $day2 . "'";
						$show_do = $this->dtr_model->return_data_row_array($tk, $sql)['numNDO'];
					}

					//if ge offdutyhan 
					$sql = "SELECT count(empId) as numODA FROM assign_mul_sc WHERE 
									assign_mul_sc.empId = '" . $empId . "' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo AND remark != ''";
					$numODA   = $this->dtr_model->return_data_row_array($tk, $sql)['numODA'] * 1;
					if ($numODA) {
						$show_do = 0;
					}
					/*
					// check if na assignan sa day off	
						$sql_do = "SELECT amsId FROM assign_mul_sc WHERE empId = '".$empId."' AND
												status = 'active' AND status2 = 'Approved' AND '".$tempDate."' BETWEEN dateFrom AND dateTo 
												ORDER BY id DESC";
						$amsId 	= $this->dtr_model->return_data_row_array($tk, $sql_do)['amsId'];

						$show_do = 0;
						if(!empty($amsId)){

							$sq_do = "SELECT count(ass_mul_do.empId) as numDO FROM ass_mul_do WHERE 
												  ass_mul_do.amsId = '".$amsId."' AND ass_mul_do.empId = '".$empId."' AND ass_mul_do.date = '".$tempDate."'";
							$numDO = $this->dtr_model->return_data_row_array($tk, $sq_do)['numDO'];
							if($numDO != 0){
								$show_do = 1;
							}
						}

						if(empty($amsId)){
							$sq_do = "SELECT count(assign_day_off.empId) as numDO FROM assign_day_off WHERE 
												  assign_day_off.empId = '".$empId."' AND assign_day_off.day = '".$dayB."'";
							$numDO = $this->dtr_model->return_data_row_array($tk, $sq_do)['numDO'];
							if($numDO != 0){
								$show_do = 1;
							}
						}
					// end of check if na assignan sa day off	

					// if nag change day off short term	
						// new assign day -off
							$sq_do = "SELECT count(ass_change_do.empId) as numDO, ass_change_do.dateNew, approveby.date, approveby.time 
												  FROM ass_change_do, approveby WHERE 
												  ass_change_do.acdId = approveby.requestId AND approveby.requestType = 'CDOS' AND
												  ass_change_do.empId = '".$empId."' AND ass_change_do.dateNew = '".$tempDate."' AND ass_change_do.status = 'Approved'";
							$s_do  = $this->dtr_model->return_data_row_array($tk, $sq_do);
							$numDO 	 = $s_do['numDO'];	
							$dateReq = $s_do['date'];	
							$timeReq = $s_do['time'];
							$dateNew = $s_do['dateNew'];

							if($numDO != 0){
								$show_do = 1;
								// check if naa assignan ug utro balik
									$sql_do ="SELECT amsId FROM assign_mul_sc WHERE empId = '".$empId."' AND
															status = 'active' AND status2 = 'Approved' AND '".$tempDate."' BETWEEN dateFrom AND dateTo 
															ORDER BY id DESC";
									$amsId 	= $this->dtr_model->return_data_row_array($tk, $sql_do)['amsId'];		
									
									//check kon unsa nga day-off ge assign
										$sql_do 	 = "SELECT ass_mul_do.date FROM ass_mul_do WHERE 
																empId = '".$empId."' AND amsId = '".$amsId."'";
										$date_off_cs = $this->dtr_model->return_row_array($tk, $sql_do)['date'];									
										
									$consh  	= "SELECT dateReq, timeReq FROM request_new_shift WHERE 
														  		amsId = '".$amsId."'";
									$con    	= $this->dtr_model->return_data_row_array($tk, $consh);
									$dateReq_sc = $con['dateReq'];
									$timeReq_sc = $con['timeReq'];
																			
									$date_req 	 = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));																
									$date_req_sc = date("Y-m-d H:i:s", strtotime("$dateReq_sc $timeReq_sc"));

									if(strtotime($date_req_sc) > strtotime($date_req) && $dateNew != $date_off_cs && !empty($date_off_cs)){
										$show_do = 0;
									}
								// end of if check if naa assignan ug utro balik
							}	
						// end of new assign day -off	
*/

					$sq_do 	 = "SELECT count(ass_change_do.empId) as numDO, approveby.date, approveby.time, ass_change_do.dateNew
											  FROM ass_change_do, approveby WHERE 
											  ass_change_do.acdId = approveby.requestId AND approveby.requestType = 'CDOS' AND
											  ass_change_do.empId = '" . $empId . "' AND ass_change_do.dateToChange = '" . $tempDate . "' AND ass_change_do.status = 'Approved'";
					$s_do  	 	= $this->dtr_model->return_data_row_array($tk, $sq_do);
					$numDO 	 	= $s_do['numDO'];
					$dateReq 	= $s_do['date'];
					$timeReq 	= $s_do['time'];
					$dateNew 	= $s_do['dateNew'];

					if ($numDO != 0) {
						$show_do = 0;

						// check if naa assignan ug utro balik
						$sql_do = "SELECT amsId FROM assign_mul_sc WHERE empId = '" . $empId . "' AND
															status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo 
															ORDER BY id DESC";
						$amsId 	= $this->dtr_model->return_data_row_array($tk, $sql_do)['amsId'];

						//check kon unsa nga day-off ge assign
						$sql_do 	 = "SELECT ass_mul_do.date FROM ass_mul_do WHERE 
																ass_mul_do.empId = '" . $empId . "' AND ass_mul_do.amsId = '" . $amsId . "'";
						$date_off_cs = $this->dtr_model->return_data_row_array($tk, $sql_do)['date'];


						$consh  	= "SELECT request_new_shift.dateReq, timeReq FROM request_new_shift WHERE 
														  		request_new_shift.amsId = '" . $amsId . "'";
						$con    	= $this->dtr_model->return_data_row_array($tk, $consh);
						$dateReq_sc = $con['dateReq'];
						$timeReq_sc = $con['timeReq'];

						$date_req 	 = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));
						$date_req_sc = date("Y-m-d H:i:s", strtotime("$dateReq_sc $timeReq_sc"));

						if ($date_req_sc  > $date_req && !empty($dateReq_sc) && $dateNew != $date_off_cs && !empty($date_off_cs)) {
							$show_do = 1;
						}
					}

					// check if na off dutyhan ba	
					if ($show_do == 1) {

						$sql_m_d = "SELECT amsId FROM assign_mul_sc WHERE 
												     empId = '" . $empId . "' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo";
						$query_sql_m_d = $this->dtr_model->return_data_result_array($tk, $sql_m_d);
						foreach ($query_sql_m_d as $sq_m) {

							$amsId 	= $sq_m['amsId'];

							$consh_m  	= "SELECT count(request_odaf.amsId) as numODA FROM request_odaf WHERE 
													  	   request_odaf.amsId = '" . $amsId . "'";
							$numODA_m   = $this->dtr_model->return_data_row_array($tk, $consh_m)['numODA'];
							if ($numODA_m != 0) {
								$show_do = 0;
							}
						}
					}

					// check if no schedule
					$no_schedule = "";
					if (strtolower($promo_type) == "roving") {

						$sql_store = "SELECT noStore FROM promo_mul_sc WHERE empId = '" . $empId . "' AND dateRendered = '" . $tempDate . "' ORDER BY pId DESC";
						$no_store = $this->dtr_model->return_data_row_array($tk, $sql_store)['noStore'];
						// echo $tempDate."=".$no_store."<br>";
						if ($no_store == "") {

							$sql = "SELECT noStore FROM promo_def_sched WHERE empId = '" . $empId . "' AND day_sched = '" . $day . "' ORDER BY pdId DESC";
							$no_store = $this->dtr_model->return_data_row_array($tk, $sql)['noStore'];

							if ($no_store != "") {

								$no_schedule = "true";
							}
						} else {

							if ($no_store == 0) {

								$no_schedule = "true";
							} else {

								$no_schedule = "";
							}
						}
					} else {

						$sql_do = "SELECT amsId FROM assign_mul_sc WHERE empId = '" . $empId . "' AND
													status = 'active' AND status2 = 'Approved' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo 
													ORDER BY id DESC";
						$amsId 	= $this->dtr_model->return_data_row_array($tk, $sql_do)['amsId'];

						$sql_store = "SELECT COUNT(id) AS exist FROM promo_nosched WHERE empId = '" . $empId . "' AND amsId = '" . $amsId . "' AND date = '" . $tempDate . "'";
						$no_sched 	= $this->dtr_model->return_data_row_array($tk, $sql_store)['exist'];

						if ($no_sched == 1) {
							$no_schedule = "true";
						}
					}

					//sil
					$sql_req = "SELECT count(r_l_absence.status) as numSIL, typeLeave FROM r_l_absence, rla_day WHERE 
										    r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' AND
										    '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND status = 'Approved' AND
										    (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%')";
					$sq_req = $this->dtr_model->return_data_row_array($tk, $sql_req);
					$numSIL	= $sq_req['numSIL'];
					//end of sil

					// end of check if na off duty va
					if ($yesHol == "true" && $numODA == 0) {

						$pdf->Cell(22, 3, $date, 0, 0);
						$pdf->Cell(11, 3, $day, 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(40, 3, "$nH [ $cat ]", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
					} else if ($show_do == 1) {

						$pdf->Cell(22, 3, $date, 0, 0);
						$pdf->Cell(11, 3, $day, 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(40, 3, "DAYOFF", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
					} else if ($no_schedule == "true" && !$dutyl) {

						$pdf->Cell(22, 3, $date, 0, 0);
						$pdf->Cell(11, 3, $day, 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(40, 3, "NO SCHEDULE", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
					} else if ($numSIL != 0) {

						$pdf->Cell(22, 3, $date, 0, 0);
						$pdf->Cell(11, 3, $day, 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(40, 3, "SIL", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(20, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
						$pdf->Cell(12, 3, "", 0, 0);
					} else {

						$pdf->Cell(22, 3, $date, 0, 0);
						$pdf->Cell(11, 3, $day, 0, 0);
						$pdf->Cell(20, 3, $in1_store . '' . $in1, 0, 0);
						$pdf->Cell(20, 3, $out1_store . '' . $out1, 0, 0);
						$pdf->Cell(20, 3, $in2_store . '' . $in2, 0, 0);
						$pdf->Cell(20, 3, $out2_store . '' . $out2, 0, 0);
						$pdf->Cell(20, 3, $in3_store . '' . $in3, 0, 0);
						$pdf->Cell(20, 3, $out3_store . '' . $out3, 0, 0);
						$pdf->Cell(12, 3, $hw, 0, 0);
						$pdf->Cell(12, 3, $newHM, 0, 0);
						$pdf->Cell(12, 3, $oT, 0, 0);
					}

					$pdf->Ln('4');
					$dateFrom = date("Y-m-d", strtotime($dateFrom . "+1 day"));
				}

				// Previous Cut Off
				$sql = "SELECT * FROM perday_previous WHERE empId = '$empId' AND coDF1 = '" . $fetch['dateFrom'] . "' AND coDF2 = '" . $fetch['dateTo'] . "'";
				$sql2 = "SELECT * FROM perday_previous_allow WHERE empId = '$empId' AND dateFrom = '" . $fetch['dateFrom'] . "' AND dateTo = '" . $fetch['dateTo'] . "'";
				if ($this->dtr_model->return_data_num_rows($tk, $sql) > 0 && $this->dtr_model->return_data_num_rows($tk, $sql2) > 0) {
					$pdf->Ln(4);
					$pdf->SetFont('times', 'B', 9, 'L');
					$pdf->Cell(20, 5, 'Previous Days Worked', 0, 0, 'L');
					$pdf->SetFont('times', '', 9, 'L');
					$pdf->Ln(2);
					$pdf->Cell(20, 5, '__________________________________________________________________________________________________________________', 0, 0, '');
					$pdf->Ln(6);

					$dw_addOn = $this->dtr_model->return_data_result_array($tk, $sql);
					foreach ($dw_addOn as $nf) {

						$dateDuty 	= $nf['dateDuty'];

						$hw_new 	= floatval($nf['hw_new']) * 1;
						$hw_con 	= $nf['hw_con'] + 0;
						$request 	= $nf['request'];
						$dayS 		= date("D", strtotime($dateDuty));
						$dateD 		= date("m/d/Y", strtotime($dateDuty));

						$sql = "SELECT dutyId, duty.date, in1, out1, in2, out2, in3, out3 
											 FROM duty
											 WHERE bioMetricId = '" . $bioM . "' AND date = '" . $dateDuty . "'";
						$dtr = $this->dtr_model->return_data_row_array($tk, $sql);

						$dutyId = $dtr['dutyId'];
						$in1 	= $dtr['in1'];
						$out1 	= $dtr['out1'];

						$in2 	= $dtr['in2'];
						$out2 	= $dtr['out2'];

						$in3 	= $dtr['in3'];
						$out3 	= $dtr['out3'];

						// store entry
						$in1_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in1, "I");
						$out1_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out1, "O");
						$in2_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in2, "I");
						$out2_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out2, "O");
						$in3_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in3, "I");
						$out3_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out3, "O");

						if (empty($out3_store)) {
							$out3_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O");
						}

						if (!empty($in1)) {
							$in1 = date('h:i A', strtotime($in1));
						}

						if (!empty($out1)) {
							$out1 = date('h:i A', strtotime($out1));
						}

						if (!empty($in2)) {
							$in2 = date('h:i A', strtotime($in2));
						}

						if (!empty($out2)) {
							$out2 = date('h:i A', strtotime($out2));
						}

						if (!empty($in3)) {
							$in3 = date('h:i A', strtotime($in3));
						}

						if (!empty($out3)) {

							$out3 = date('h:i A', strtotime($out3));
						}

						$pdf->Cell(22, 3, $dateD, 0, 0);
						$pdf->Cell(11, 3, $dayS, 0, 0);
						$pdf->Cell(20, 3, $in1_store . '' . $in1, 0, 0);
						$pdf->Cell(20, 3, $out1_store . '' . $out1, 0, 0);
						$pdf->Cell(20, 3, $in2_store . '' . $in2, 0, 0);
						$pdf->Cell(20, 3, $out2_store . '' . $out2, 0, 0);
						$pdf->Cell(20, 3, $in3_store . '' . $in3, 0, 0);
						$pdf->Cell(20, 3, $out3_store . '' . $out3, 0, 0);
						$pdf->Cell(12, 3, $hw_new, 0, 0);
						$pdf->Cell(12, 3, $hw_con, 0, 0);
						$pdf->Cell(12, 3, "0", 0, 0);
						$pdf->Ln('4');
					}
				}

				// Previous OT
				$sql = "SELECT otDate, otApp FROM otforpayment WHERE emp_id = '$empId' AND supDF = '" . $fetch['dateFrom'] . "' AND supDT = '" . $fetch['dateTo'] . "' AND otDate < '" . $fetch['dateFrom'] . "'";
				if ($this->dtr_model->return_data_num_rows($tk, $sql) > 0) {
					$pdf->Ln(4);
					$pdf->SetFont('times', 'B', 9, 'L');
					$pdf->Cell(20, 5, 'Previous OT', 0, 0, 'L');
					$pdf->SetFont('times', '', 9, 'L');
					$pdf->Ln(2);
					$pdf->Cell(20, 5, '__________________________________________________________________________________________________________________', 0, 0, '');
					$pdf->Ln(6);

					$ot_addon = $this->dtr_model->return_data_result_array($tk, $sql);
					foreach ($ot_addon as $ot) {

						$dateDuty = $ot['otDate'];
						$otApproved = floatval($ot['otApp']) * 1;
						$dayS 		= date("D", strtotime($dateDuty));
						$dateD 		= date("m/d/Y", strtotime($dateDuty));

						$sql = "SELECT * FROM duty WHERE bioMetricId = '$bioM' AND date = '$dateDuty'";
						$dtr = $this->dtr_model->return_data_row_array($tk, $sql);

						$dutyId = $dtr['dutyId'];
						$in1 	= $dtr['in1'];
						$out1 	= $dtr['out1'];

						$in2 	= $dtr['in2'];
						$out2 	= $dtr['out2'];

						$in3 	= $dtr['in3'];
						$out3 	= $dtr['out3'];

						// store entry
						$in1_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in1, "I");
						$out1_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out1, "O");
						$in2_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in2, "I");
						$out2_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out2, "O");
						$in3_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $in3, "I");
						$out3_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dateDuty, $out3, "O");

						if (empty($out3_store)) {
							$out3_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($dateDuty . " +1 day")), $out3, "O");
						}

						if (!empty($in1)) {
							$in1 = date('h:i A', strtotime($in1));
						}

						if (!empty($out1)) {
							$out1 = date('h:i A', strtotime($out1));
						}

						if (!empty($in2)) {
							$in2 = date('h:i A', strtotime($in2));
						}

						if (!empty($out2)) {
							$out2 = date('h:i A', strtotime($out2));
						}

						if (!empty($in3)) {
							$in3 = date('h:i A', strtotime($in3));
						}

						if (!empty($out3)) {

							$out3 = date('h:i A', strtotime($out3));
						}

						$pdf->Cell(22, 3, $dateD, 0, 0);
						$pdf->Cell(11, 3, $dayS, 0, 0);
						$pdf->Cell(20, 3, $in1_store . '' . $in1, 0, 0);
						$pdf->Cell(20, 3, $out1_store . '' . $out1, 0, 0);
						$pdf->Cell(20, 3, $in2_store . '' . $in2, 0, 0);
						$pdf->Cell(20, 3, $out2_store . '' . $out2, 0, 0);
						$pdf->Cell(20, 3, $in3_store . '' . $in3, 0, 0);
						$pdf->Cell(20, 3, $out3_store . '' . $out3, 0, 0);
						$pdf->Cell(12, 3, '0', 0, 0);
						$pdf->Cell(12, 3, '0', 0, 0);
						$pdf->Cell(12, 3, $otApproved, 0, 0);
						$pdf->Ln('4');
					}
				}


				$pdf->Cell(20, 5, '__________________________________________________________________________________________________________________', 0, 0, '');
				$pdf->Ln(5);

				$pdf->SetFont('times', '', 7, 'L');
				$pdf->Cell(20, 5, "Legend");
				$pdf->Ln(4);
				$pdf->Cell(20, 5, "A - ASC: Main");
				$pdf->SetX(30);
				$pdf->Cell(0, 5, "B - Alturas Talibon");
				$pdf->SetX(52);
				$pdf->Cell(0, 5, "C - Alturas Tubigon");
				$pdf->SetX(75);
				$pdf->Cell(0, 5, "D - Island City Mall");
				$pdf->SetX(99);
				$pdf->Cell(0, 5, "E - Plaza Marcela");
				$pdf->SetX(120);
				$pdf->Cell(0, 5, "F - Colonnade- Colon");
				$pdf->SetX(145);
				$pdf->Cell(0, 5, "G - Colonnade- Mandaue");
				$pdf->SetX(176);
				$pdf->Cell(0, 5, "H - Alta Citta");
				$pdf->Ln(4);
				$pdf->Cell(20, 5, "I - Alturas Panglao");

				if ($days == 16) {
					$pdf->Ln(24);
				} else if ($days == 14 || $days == 13) {
					$pdf->Ln(42);
				} else {
					$pdf->Ln(26);
				}
			}
		}

		//Close and output PDF document
		$pdf->Output('promo_dtr.pdf', 'I');
	}

	public function print_violation()
	{

		$fetch = $this->input->get(NULL, TRUE);
		$violation = $this->violation_model->get_promo_violation($fetch);

		// create new PDF document
		$this->load->library('Pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Zoren  Ormido');
		$pdf->SetTitle('Promo Violation');
		$pdf->SetSubject('Violation');
		$pdf->SetKeywords('Violation, promodiser, merchandiser, diser, promo');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(12, 12, 12);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 15);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('times', '', 10);

		// add a page
		$pdf->AddPage();

		// set margins
		$pdf->SetMargins(12, 12, 12, 12);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



		// Image method signature:
		// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

		$x = 1;
		foreach ($violation as $v) {

			$pdf->Image(base_url() . 'admins/' . $v->image_path, '', '', '', '', '', '', '', false, 150, '', false, false, 0, false, false, true);

			// add a page
			if ($x < count($violation)) {
				$pdf->AddPage();
			}
			$x++;
		}

		$pdf->Output('promo_violation.pdf', 'I');
	}

	public function print_employee_violation()
	{
		$fetch = $this->input->get(NULL, TRUE);
		$violation = $this->violation_model->promo_violation_details($fetch);

		// create new PDF document
		$this->load->library('Pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Zoren  Ormido');
		$pdf->SetTitle('Promo Violation');
		$pdf->SetSubject('Violation');
		$pdf->SetKeywords('Violation, promodiser, merchandiser, diser, promo');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(12, 12, 12);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 15);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// add a page
		$pdf->AddPage();

		$image_file = base_url() . 'assets/img/logo/agc_logo.jpg';
		$pdf->Image($image_file, 25, 20, 40, 10, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// set font
		$pdf->SetFont('times', 'B', 16);
		$pdf->SetX(30);
		$pdf->Cell(0, 5, 'Alturas Supermarket Corporation', 0, 1, 'C');
		$pdf->SetFont('times', '', 12);
		$pdf->SetX(30);
		$pdf->Cell(0, 5, 'Dampas District, Tagbilaran City, Philippines', 0, 1, 'C');
		$pdf->SetX(30);
		$pdf->Cell(0, 5, 'Tel. No: (038) 501-3000; Local: 1313; Fax No: 501-9245', 0, 1, 'C');
		$pdf->SetX(30);
		$pdf->Cell(0, 5, 'Email Add: corporatehrd@alturasbohol.com', 0, 1, 'C');

		$pdf->Ln(15);
		if (!empty($violation['agency'])) {

			$supplier = $violation['agency'];
		} else {

			$supplier = $violation['company'];
		}

		$pdf->SetFont('times', 'B', 13);
		$pdf->Cell(0, 0, $supplier, 0, 1, 'L');

		$pdf->Ln(1);

		$pdf->SetFont('times', '', 12);
		$html = "<div style='padding-top: 35px;'>
					<p>Dear Sir/Ma'am:</p>
					<p style='margin-top:30px;'>This is in reference to you merchandiser, <b>MR. " . strtoupper($violation['fullname']) . "</b> presently assigned in our establishment handling <b>" . $violation['company'] . "</b> wherein he/she violated our company House Rules and Regulations with suspension/s as a corresponding disciplinary action.</p>
				</div>";
		$pdf->writeHTML($html, true, false, true, false, 'J');

		$pdf->SetFont('times', '', 10);

		$subtable = '';
		foreach ($violation['violation_detail'] as $key) {
			$subtable .= '<tr>
				<td align="center">' . $key['violation'] . '</td>
				<td align="center">' . $key['store'] . '- ' . $key['department'] . '</td>
				<td>';
			if (count($key['violation_prev_details']) > 0) {

				$subtable .= 'PREVIOUS : <br><br>';
				foreach ($key['violation_prev_details'] as $prev_detail) {

					$subtable .= $prev_detail->detail . '<br>';
				}
				$subtable .= '<hr>';
			}

			$subtable .= 'PRESENT : <br><br>';
			foreach ($key['violation_details'] as $detail) {

				$subtable .= $detail->detail . '<br>';
			}

			$subtable .= '
				</td>
				<td align="center">' . $key['suspension'] . ' day</td>
			</tr>';
		}


		$html = '<table cellpadding="3" cellspacing="1" border="1" width="100%">
					<tr>
						<th width="20%"align="center"><b>Violations Commited</b></th>
						<th width="20%" align="center"><b>Area of Assignment</b></th>
						<th width="45%" align="center"><b>Violation Details</b></th>
						<th width="15%" align="center"><b>No. of <br> Suspension/s</b></th>
					</tr>
					' . $subtable . '
				</table>';
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->SetFont('times', '', 12);
		$html = '<div style="padding-top: 20px;">
					<p>The above infraction/s forms part of his/her negative performance and attitude at work, hence we are requesting for an immidiate action regarding this matter.</p>
					<p style="margin-top:30px;">Thank you and more power.</p>
					<p style="margin-top:30px;">Respectfully yours,</p>
					<p style="margin-top:50px;"><b>MARIA NORA PAHANG</b></p>
					<p>HRD Manager</p>
				</div>';
		$pdf->writeHTML($html, true, false, true, false, '');

		//Close and output PDF document
		$pdf->Output('Promo Violation.pdf', 'I');
	}
}
