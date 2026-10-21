<?php

if ($request == "employee_list") {

?>

	<input type="hidden" name="cutoff" value="<?php echo $fetch['cutoff']; ?>">
	<input type="hidden" name="cut" value="<?php echo $fetch['cut']; ?>">
	<input type="hidden" name="dateFrom" value="<?php echo $fetch['dateFrom']; ?>">
	<input type="hidden" name="dateTo" value="<?php echo $fetch['dateTo']; ?>">
	<input type="hidden" name="userId" value="<?php echo $_SESSION['userId']; ?>">
	<input type="hidden" name="supplier_id" value="<?php echo $_SESSION['supplier_id']; ?>">
	<input type="hidden" name="supplier_type" value="<?php echo $supplier_type; ?>">

	<div class="alert alert-info" style="border-left: 4px solid; border-left-color: currentcolor;" role="alert">
		<h4 class="alert-heading">Reminders!</h4>
		<p>Updating and uploading of entries, time schedule, request will be posted a day after the current date.</p>

		<?php

		$today = date('Y-m-d');
		$day_before = date('Y-m-d', strtotime($fetch['dateTo'] . ' -1 day'));

		if (strtotime($today) >= strtotime($fetch['dateFrom']) && strtotime($today) <= strtotime($fetch['dateTo'])) {

			if ($today == $day_before) {

				echo '<p class="mb-0">DTR entries are still up to final posting, two(2) days after cut-off.</p>';
			}
		}
		?>
	</div>

	<div class="checkout">
		<div class="checkout-header">
			<div class="row">
				<?php if (isset($alturas)) { ?>
					<div class="col-md-3 col-sm-3">
						<div class="step active step_tagbilaran">
							<a href="javascript:void(0);" id="tagbilaran" class="server">
								<div class="number">1</div>
								<div class="info">
									<div class="title">Tagbilaran, Bohol</div>
									<div class="desc">Alturas Mall, ICM , Plaza Marcela, Alta Citta & Fixrite Panglao</div>
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="step step_tubigon">
							<a href="javascript:void(0);" id="tubigon" class="server">
								<div class="number">2</div>
								<div class="info">
									<div class="title">Tubigon, Bohol</div>
									<div class="desc">Alturas Tubigon</div>
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="step step_talibon">
							<a href="javascript:void(0);" id="talibon" class="server">
								<div class="number">3</div>
								<div class="info">
									<div class="title">Talibon, Bohol</div>
									<div class="desc">Alturas Talibon</div>
								</div>
							</a>
						</div>
					</div>
				<?php } else { ?>
					<div class="col-md-3 col-sm-3">
						<div class="step active step_tagbilaran">
							<div class="number">1</div>
							<div class="info">
								<div class="title">Tagbilaran, Bohol</div>
								<div class="desc">Alturas Mall, ICM , Plaza Marcela & Alta Citta</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="step step_tubigon_">
							<div class="number">2</div>
							<div class="info">
								<div class="title">Tubigon, Bohol</div>
								<div class="desc">Alturas Tubigon</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="step step_talibon">
							<div class="number">3</div>
							<div class="info">
								<div class="title">Talibon, Bohol</div>
								<div class="desc">Alturas Talibon</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="col-md-3 col-sm-3">
					<div class="step step_colonnade">
						<?php if (isset($colonnade)) { ?>
							<a href="javascript:void(0);" id="colonnade" class="server">
								<div class="number">4</div>
								<div class="info">
									<div class="title">Cebu</div>
									<div class="desc">Colonnade Colon & Mandaue</div>
								</div>
							</a>
						<?php } else { ?>
							<div class="number">4</div>
							<div class="info">
								<div class="title">Cebu</div>
								<div class="desc">Colonnade Colon & Mandaue</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="checkout-body">
			<input type="hidden" name="server" value="tagbilaran">
			<div class="table-responsive">
				<table id="dt_emp_list" class="table table-striped table-bordered table-hover" width="100%">
					<thead>
						<tr>
							<th> Fullname</th>
							<th> Company</th>
							<th> BusinessUnit</th>
							<th> Position</th>
							<th> PromoType</th>
							<th> Action</th>
							<th>
								<input type="checkbox" class="chkA chk_0" onclick="chk(0)">
							</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {

			var cutoff = $("input[name = 'cutoff']").val();
			var dateFrom = $("input[name = 'dateFrom']").val();
			var dateTo = $("input[name = 'dateTo']").val();
			employee_list(cutoff, "tagbilaran", dateFrom, dateTo);

			$("a.server").click(function(e) {

				e.preventDefault();
				var id = $(this).attr('id');

				$("div.step").removeClass('active');
				$("div.step_" + id).addClass('active');
				$("input[name = 'server']").val(id.trim());

				employee_list(cutoff, id, dateFrom, dateTo);
			});
		});
	</script>
<?php
} else if ($request == "view_dtr") {
	$server = $fetch['server'];
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

	$dateFrom = date('M. d, Y', strtotime($fetch['dateFrom']));
	$dateTo = date('M. d, Y', strtotime($fetch['dateTo']));

	if ($fetch['cut'] == 1) {

		$cutOff = "1stcutoff";
		$id = "fCOId";
	} else {

		$cutOff = "2ndcutoff";
		$id = "sCOId";
	}

	// biometric ID
	$bioM = '';
	$bio = "SELECT assign.bioMetricId, assign.barcodeId, assign.allowId FROM assign WHERE assign.empId = '" . $fetch['empId'] . "' ORDER BY assignId DESC LIMIT 1";
	$b = $this->dtr_model->return_data_row_array($tk, $bio);

	$allowId = $b['allowId'];
	if ($allowId == "Biometric ID") {

		$bioM = $b['bioMetricId'];
	}

	if ($allowId == "Logbox ID") {

		$bioM = $b['barcodeId'];
	}

	$info = "SELECT name, promo_company, promo_department, promo_type
					FROM employee3, promo_record
					WHERE
					employee3.record_no = promo_record.record_no AND employee3.emp_id = promo_record.emp_id AND
					employee3.emp_id = '" . $fetch['empId'] . "' ORDER BY employee3.record_no DESC LIMIT 1";
	$ass = $this->dtr_model->return_data_row_array($pis, $info);
	$name = $ass['name'];
	$promo_company = $ass['promo_company'];
	$promo_department = $ass['promo_department'];
	$promo_type = $ass['promo_type'];

	// daysworked
	$daysWrk = "SELECT daysWork, otForPayment, netOT, legHol, speHol FROM $cutOff WHERE bioMetricId = '" . $bioM . "' AND 
							dateFrom = '" . $fetch['dateFrom'] . "' AND dateTo = '" . $fetch['dateTo'] . "' ORDER BY $id DESC";
	$g = $this->dtr_model->return_data_row_array($tk, $daysWrk);

	$dw = $totalOvertime = $legalHoliday = $specialHoliday = '';
	if (!empty($g)) {

		$dw = $g['daysWork'];
		$totalOvertime = $g['otForPayment'];
		$legalHoliday = $g['legHol'];
		$specialHoliday = $g['speHol'];
	}

	if (!empty($totalOvertime)) {

		$totalOvertime = "$totalOvertime";
	} else {

		$totalOvertime = "0";
	}

	// Service Incentive Leave 
	$sil = 0;

	//current
	$sil_prev = 0;
	$sil_cu = 0;

	$cu = $this->dtr_model->no_sil_current($tk, $fetch);
	if (!empty($cu)) {

		$sil_cu = $cu['sil'];
	}

	//previous
	$prev = $this->dtr_model->no_sil_previous($tk, $fetch);
	if (!empty($prev)) {

		$sil_prev = $prev['sil'];
	}

	$sil       = $sil_prev + $sil_cu;

	$photo = $this->dtr_model->get_photo($fetch['empId'], $pis)['photo'];
	$pic = explode("../images/users/", $photo);
	$picture = end($pic);

	if (!file_exists("assets/img/users/$picture")) {

		$picture = "admins/assets/img/user/user-1.png";
	} else {

		$picture = "assets/img/users/$picture";
	}

?>
	<input type="hidden" name="cut" value="<?php echo $fetch['cut']; ?>">
	<input type="hidden" name="dateFrom" value="<?php echo $fetch['dateFrom']; ?>">
	<input type="hidden" name="dateTo" value="<?php echo $fetch['dateTo']; ?>">
	<input type="hidden" name="server" value="<?php echo $fetch['server']; ?>">
	<input type="hidden" name="cutoff" value="<?php echo $fetch['cutoff']; ?>">
	<input type="hidden" name="chkEmpId[]" value="<?php echo $bioM; ?>">
	<div class="row">
		<div class="col-md-2">
			<img src="<?php echo base_url($picture); ?>" loading="lazy" alt="User Image" class="img-thumbnail" width="140">
		</div>
		<div class="col-md-10">
			<table class="table">
				<tr>
					<th width="13%">Fullname</th>
					<td> : <?php echo ucwords(strtolower($name)); ?></td>
					<th>Daysworked</th>
					<td> : <?php echo $dw; ?></td>
					<th>Legal Holiday</th>
					<td> : <?php echo $legalHoliday; ?></td>
				</tr>
				<tr>
					<th>Company</th>
					<td> : <?php echo $promo_company; ?></td>
					<th>Overtime</th>
					<td> : <?php echo $totalOvertime; ?></td>
					<th>SIL</th>
					<td> : <?php echo $sil; ?></td>
				</tr>
				<tr>
					<th>Department</th>
					<td> : <?php echo $promo_department; ?></td>
					<th>Special Holiday</th>
					<td colspan="3">: <?php echo $specialHoliday; ?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table id="dt_dtrEntry" class="table table-hover" width="100%">
				<thead>
					<tr>
						<th>Date</th>
						<th>Day</th>
						<th>IN1</th>
						<th>OUT1</th>
						<th>IN2</th>
						<th>OUT2</th>
						<th>IN3</th>
						<th>OUT3</th>
						<th>HW</th>
						<th>DW</th>
						<th>OT</th>
						<th>App. Req</th>
						<th>E</th>
					</tr>
				</thead>
				<tbody>
					<?php

					while (strtotime($dateFrom) <= strtotime($dateTo)) {

						$day = date('D', strtotime($dateFrom));
						$dF = date('Y-m-d', strtotime($dateFrom));

						$dutyId = '';
						$in1_store = $out1_store = $in2_store = $out2_store	= $in3_store = $out3_store = "";
						$in1 = $out1 = $in2 = $out2 = $in3 = $out3 = "";

						$tempDate = date('Y-m-d', strtotime($dateFrom));
						$sql = "SELECT dutyId, duty.date, in1, out1, in2, out2, in3, out3 
														 FROM duty
														 WHERE bioMetricId = '" . $bioM . "' AND date = '" . $tempDate . "' ORDER BY dutyId DESC";

						$dtr = $this->dtr_model->return_data_row_array($tk, $sql);

						@$dutyId = $dtr['dutyId'];
						@$in1 	= $dtr['in1'];
						@$out1 	= $dtr['out1'];

						@$in2 	= $dtr['in2'];
						@$out2 	= $dtr['out2'];

						@$in3 	= $dtr['in3'];
						@$out3 	= $dtr['out3'];

						// store entry
						$in1_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dF, $in1, "I");
						$out1_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dF, $out1, "O");
						$in2_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dF, $in2, "I");
						$out2_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dF, $out2, "O");
						$in3_store 	= $this->dtr_model->store_entry($tk, $bioM, $allowId, $dF, $in3, "I");
						$out3_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, $dF, $out3, "O");

						if (empty($out3_store)) {
							$out3_store = $this->dtr_model->store_entry($tk, $bioM, $allowId, date('Y-m-d', strtotime($dF . " +1 day")), $out3, "O");
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

						$hours_work = 0;
						$oT = 0;

						$hh = "SELECT hoursWork FROM perday WHERE dutyId = '" . $dutyId . "'";
						$perday = $this->dtr_model->return_data_row_array($tk, $hh);
						if (!empty($perday)) {

							$hours_work = $perday['hoursWork'];
						}

						$res = explode("&", $hours_work);

						if ($res[0] == "") {
							$res[0] = 0;
						}

						if (@$res[1] == "") {
							$res[1] = 0;
						}

						$h = floatval($res[0]) * 1;
						$m = floatval($res[1]) * 1;

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
						$otApp = 0;
						$query = "SELECT otApp FROM otforpayment WHERE emp_id = '" . $fetch['empId'] . "' AND otDate = '" . $tempDate . "' ORDER BY pay_id DESC";
						$qt = $this->dtr_model->return_data_row_array($tk, $query);

						if (!empty($qt)) {
							$otApp = $qt['otApp'];
						}
						$oT = floatval($otApp) * 1;

						if (empty($oT) || $oT == 0) {
							$oT = 0;
						}


						$dayB = date('l', strtotime($dateFrom));
						// check if holiday	

						$nameH1 = '';
						$cat1  	= '';
						$chkh 	= "SELECT nameHoliday, category FROM holidays_promo WHERE 
															 date = '" . $tempDate . "' AND moveDate = '' LIMIT 1";
						$ch   	= $this->dtr_model->return_data_row_array($tk, $chkh);
						$numH1  = $this->dtr_model->return_data_num_rows($tk, $chkh);

						if (!empty($ch)) {
							$nameH1 = $ch['nameHoliday'];
							$cat1  	= $ch['category'];
						}


						$nameH2 = '';
						$cat2  	= '';
						$chkh 	= "SELECT nameHoliday, category FROM holidays_promo WHERE 
															   moveDate = '" . $tempDate . "' LIMIT 1";
						$ch2   	= $this->dtr_model->return_data_row_array($tk, $chkh);
						$numH2  = $this->dtr_model->return_data_num_rows($tk, $chkh);

						if (!empty($ch2)) {
							$nameH2 = $ch2['nameHoliday'];
							$cat2  	= $ch2['category'];
						}

						if ($numH1 != 0) {
							$nH 	= "<span style = 'color:red'>$nameH1</span>";
							$cat 	= "<span style = 'color:red'>$cat1</span>";
						}
						if ($numH2 != 0) {
							$nH 	= "<span style = 'color:red'>$nameH2</span>";
							$cat 	= "<span style = 'color:red'>$cat2</span>";
						}
						// end of check if holiday	

						// check if na assignan	

						$yesHol = "";
						$numODA = 0;
						if ($numH1 != 0 || $numH2 != 0) {
							$yesHol = "true";

							$query_chkdateo = "SELECT amsId FROM assign_mul_sc WHERE 
																	     empId = '" . $fetch['empId'] . "' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo";
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
						$sql_do = "SELECT amsId FROM assign_mul_sc WHERE empId = '" . $fetch['empId'] . "' AND
																status = 'active' AND status2 = 'Approved' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo 
																ORDER BY id DESC";
						$queryMSC 	= $this->dtr_model->return_data_row_array($tk, $sql_do);

						if (is_array($queryMSC) && count($queryMSC)) {

							$amsId = $queryMSC['amsId'];

							//ge assignan ug dayoff pag CS
							$sq_do = "SELECT count(ass_mul_do.empId) as numDO FROM ass_mul_do WHERE 
																  	ass_mul_do.amsId = '" . $amsId . "' AND ass_mul_do.empId = '" . $fetch['empId'] . "' AND ass_mul_do.date = '" . $tempDate . "'";
							$count = $this->dtr_model->return_data_row_array($tk, $sq_do)['numDO'];

							//short term nga day-off request
							$sq_dost  = "SELECT count(empId) as numDOST FROM ass_change_do WHERE
																	ass_change_do.empId = '" . $fetch['empId'] . "' AND ass_change_do.dateToChange = '" . $tempDate . "' AND status = 'Approved'";
							$queryCDO = $this->dtr_model->return_data_row_array($tk, $sq_dost)['numDOST'];

							//ge assignan ug new day-off pero walay change day-off nga request
							if ($count && $queryCDO == 0) {
								$show_do = 1;
							}

							//na assignan ug new day-off sa supv pero nag request ug change day-off ang emp. vice versa
							$sq_new_dost = "SELECT count(empId) as numNDOST FROM ass_change_do WHERE
																	ass_change_do.empId = '" . $fetch['empId'] . "' AND ass_change_do.dateNew = '" . $tempDate . "' AND status = 'Approved'";
							$queryCDO = $this->dtr_model->return_data_row_array($tk, $sq_new_dost)['numNDOST'];
							if ($queryCDO) {
								// echo $queryCDO."".$tempDate."<br>";

								// ge change day-off pag human ug cs sa supv
								$sql = "SELECT tb1.time, tb1.date, tb2.dateToChange FROM approveby as tb1 INNER JOIN ass_change_do as tb2 ON tb2.acdId=tb1.requestId WHERE
															tb2.empId = '" . $fetch['empId'] . "' AND tb2.dateNew = '" . $tempDate . "' AND tb1.requestType = 'CDOS' AND tb2.status = 'Approved'";
								$query = $this->dtr_model->return_data_row_array($tk, $sql);
								if (count($query)) {

									$dateApp        = $query['date'];
									$timeApp        = $query['time'];
									$dateToChange   = $query['dateToChange'];

									//date & time sa pag approved sa supvr
									$app_details    = date("Y-m-d H:i:s", strtotime("$dateApp $timeApp"));

									$sql_do_cs = "SELECT dateReq, timeReq FROM ass_mul_do as tb1 INNER JOIN request_new_shift as tb2 ON tb2.amsId=tb1.amsId WHERE
												    				tb1.empId = '" . $fetch['empId'] . "' AND tb1.date = '" . $dateToChange . "' LIMIT 1";
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

						// PHP >= 7.3
						// if(is_countable($searchdata)) {
						//     // Do something
						// }

						// // PHP >= 7.1
						// if(is_iterable($searchdata)) {
						//     // Do something
						// }

						if (is_array($queryMSC) && count($queryMSC) == 0) {
							//petsa nga bag-o ang day-off
							$sq_new_dost = "SELECT count(empId) as numNDOST FROM ass_change_do WHERE
																	ass_change_do.empId = '" . $fetch['empId'] . "' AND ass_change_do.dateNew = '" . $tempDate . "' AND status = 'Approved'";
							$queryCDO = $this->dtr_model->return_data_row_array($tk, $sq_new_dost)['numNDOST'];

							if ($queryCDO) {
								$req_do  = 1;
								$show_do = 1;
							}

							//petsa nga ang day-off ilisdan ug dili e display
							$sq_dost  = "SELECT count(empId) as numDOST FROM ass_change_do WHERE
																	ass_change_do.empId = '" . $fetch['empId'] . "' AND ass_change_do.dateToChange = '" . $tempDate . "' AND status = 'Approved'";
							$queryCDO = $this->dtr_model->return_data_row_array($tk, $sq_dost)['numDOST'];
							if ($queryCDO) {
								$old_do = 1;
							}
						}

						// default day-off
						if (is_array($queryMSC) && count($queryMSC) == 0 && $show_do == 0 && $old_do == 0) {

							$day2 = date('l', strtotime($tempDate));

							$sql = "SELECT count(empId) as numNDO FROM assign_day_off WHERE 
														assign_day_off.empId = '" . $fetch['empId'] . "' AND assign_day_off.day = '" . $day2 . "'";
							$show_do = $this->dtr_model->return_data_row_array($tk, $sql)['numNDO'];
						}

						//if ge offdutyhan 
						$sql = "SELECT count(empId) as numODA FROM assign_mul_sc WHERE 
													assign_mul_sc.empId = '" . $fetch['empId'] . "' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo AND remark != ''";
						$numODA   = $this->dtr_model->return_data_row_array($tk, $sql)['numODA'] * 1;
						if ($numODA) {
							$show_do = 0;
						}

						/* // check if na assignan sa day off	
										$sql_do = "SELECT amsId FROM assign_mul_sc WHERE empId = '".$fetch['empId']."' AND
																status = 'active' AND status2 = 'Approved' AND '".$tempDate."' BETWEEN dateFrom AND dateTo 
																ORDER BY id DESC";
										$amsId 	= $this->dtr_model->return_data_row_array($tk, $sql_do)['amsId'];

										$show_do = 0;
										if(!empty($amsId)){

											$sq_do = "SELECT count(ass_mul_do.empId) as numDO FROM ass_mul_do WHERE 
																  ass_mul_do.amsId = '".$amsId."' AND ass_mul_do.empId = '".$fetch['empId']."' AND ass_mul_do.date = '".$tempDate."'";
											$numDO = $this->dtr_model->return_data_row_array($tk, $sq_do)['numDO'];
											if($numDO != 0){
												$show_do = 1;
											}
										}

										if(empty($amsId)){
											$sq_do = "SELECT count(assign_day_off.empId) as numDO FROM assign_day_off WHERE 
																  assign_day_off.empId = '".$fetch['empId']."' AND assign_day_off.day = '".$dayB."'";
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
																  ass_change_do.empId = '".$fetch['empId']."' AND ass_change_do.dateNew = '".$tempDate."' AND ass_change_do.status = 'Approved'";
											$s_do  = $this->dtr_model->return_data_row_array($tk, $sq_do);
											$numDO 	 = $s_do['numDO'];	
											$dateReq = $s_do['date'];	
											$timeReq = $s_do['time'];
											$dateNew = $s_do['dateNew'];

											if($numDO != 0){
												$show_do = 1;
												// check if naa assignan ug utro balik
													$sql_do ="SELECT amsId FROM assign_mul_sc WHERE empId = '".$fetch['empId']."' AND
																			status = 'active' AND status2 = 'Approved' AND '".$tempDate."' BETWEEN dateFrom AND dateTo 
																			ORDER BY id DESC";
													$amsId 	= $this->dtr_model->return_data_row_array($tk, $sql_do)['amsId'];		
													
													//check kon unsa nga day-off ge assign
														$sql_do 	 = "SELECT ass_mul_do.date FROM ass_mul_do WHERE 
																				empId = '".$fetch['empId']."' AND amsId = '".$amsId."'";
														$date_off_cs = $this->dtr_model->return_data_row_array($tk, $sql_do)['date'];									
														
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


										$sq_do 	 = "SELECT count(ass_change_do.empId) as numDO, approveby.date, approveby.time, ass_change_do.dateNew
															  FROM ass_change_do, approveby WHERE 
															  ass_change_do.acdId = approveby.requestId AND approveby.requestType = 'CDOS' AND
															  ass_change_do.empId = '".$fetch['empId']."' AND ass_change_do.dateToChange = '".$tempDate."' AND ass_change_do.status = 'Approved'";
										$s_do  	 	= $this->dtr_model->return_data_row_array($tk, $sq_do);
										$numDO 	 	= $s_do['numDO'];	
										$dateReq 	= $s_do['date'];	
										$timeReq 	= $s_do['time'];	
										$dateNew 	= $s_do['dateNew'];	

										if($numDO != 0){
											$show_do = 0;

												// check if naa assignan ug utro balik
													$sql_do = "SELECT amsId FROM assign_mul_sc WHERE empId = '".$fetch['empId']."' AND
																			status = 'active' AND status2 = 'Approved' AND '$tempDate' BETWEEN dateFrom AND dateTo 
																			ORDER BY id DESC";
													$amsId 	= $this->dtr_model->return_data_row_array($tk, $sql_do)['amsId'];	
													
													//check kon unsa nga day-off ge assign
														$sql_do 	 = "SELECT ass_mul_do.date FROM ass_mul_do WHERE 
																				ass_mul_do.empId = '".$fetch['empId']."' AND ass_mul_do.amsId = '".$amsId."'";
														$date_off_cs = $this->dtr_model->return_data_row_array($tk, $sql_do)['date'];									


													$consh  	= "SELECT request_new_shift.dateReq, timeReq FROM request_new_shift WHERE 
																		  		request_new_shift.amsId = '".$amsId."'";
													$con    	= $this->dtr_model->return_data_row_array($tk, $consh);
													$dateReq_sc = $con['dateReq'];
													$timeReq_sc = $con['timeReq'];		
													
													$date_req 	 = date("Y-m-d H:i:s", strtotime("$dateReq $timeReq"));																
													$date_req_sc = date("Y-m-d H:i:s", strtotime("$dateReq_sc $timeReq_sc"));												

													if($date_req_sc  > $date_req && !empty($dateReq_sc) && $dateNew != $date_off_cs && !empty($date_off_cs)){
														$show_do = 1;
													}
										}	
									
									// check if na off dutyhan ba	
										if($show_do == 1){

											$sql_m_d = "SELECT amsId FROM assign_mul_sc WHERE 
																     empId = '".$fetch['empId']."' AND ('".$tempDate."' BETWEEN dateFrom AND dateTo) ORDER BY id DESC";
											// $query_sql_m_d = $this->dtr_model->return_data_result_array($tk, $sql_m_d);
											$amsId = @$this->dtr_model->return_data_result_array($tk, $sql_m_d)['amsId'];
											// foreach ($query_sql_m_d as $sq_m) {

											// 	$amsId 	= $sq_m['amsId'];	

												$consh_m  	= "SELECT count(request_odaf.amsId) as numODA FROM request_odaf WHERE 
																	  	   request_odaf.amsId = '".$amsId."'";
												$numODA_m   = $this->dtr_model->return_data_row_array($tk, $consh_m)['numODA'];
												if($numODA_m != 0){
													$show_do = 0;
												}
											// }											
										}
												
									// end of check if na off duty va */

						// check all request

						$empId = $fetch['empId'];
						//loa
						$sql_req = "SELECT count(r_l_absence.status) as numLOA, typeLeave, status FROM r_l_absence, rla_day WHERE 
													    r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' AND
													    '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending')";
						$sq_req = $this->dtr_model->return_data_row_array($tk, $sql_req);
						$loaStat    = $sq_req['status'];
						$numLOA	= $sq_req['numLOA'];
						//end of loa

						//dwa
						$sql_req = "SELECT count(d_w_adjustment.status) as numDWA, status
														FROM d_w_adjustment, off_date WHERE 
															d_w_adjustment.empId = '$empId' AND d_w_adjustment.empId = off_date.empId AND off_date.date = '$tempDate' AND
															'" . $tempDate . "' BETWEEN d_w_adjustment.dateFrom AND d_w_adjustment.dateTo AND (status = 'Approved' OR status = 'Pending')";
						$sq_req = $this->dtr_model->return_data_row_array($tk, $sql_req);
						$dwaStat    = $sq_req['status'];
						$numDWA	 = $sq_req['numDWA'];

						if ($numDWA == 0) {

							$sql_req = "SELECT count(d_w_adjustment.status) as numDWA, status
															FROM d_w_adjustment WHERE 
															 	d_w_adjustment.empId = '$empId' AND 
															 	(d_w_adjustment.type = 'Failure' OR d_w_adjustment.type = 'System Error' OR d_w_adjustment.type = 'Wrong Mode') AND
																'$tempDate' BETWEEN d_w_adjustment.dateFrom AND d_w_adjustment.dateTo AND (status = 'Approved' OR status = 'Pending')";
							$sq_req = $this->dtr_model->return_data_row_array($tk, $sql_req);
							$dwaStat    = $sq_req['status'];
							$numDWA	 = $sq_req['numDWA'];
						}
						//end of dwa

						/*//eoa
										$sql_req = "SELECT count(e_o_authorization.status) as numEOA FROM e_o_authorization WHERE e_o_authorization.empId = '$empId' AND
																e_o_authorization.dateOvertime = '$tempDate' AND (status = 'Approved' OR status = 'Pending')";
										$sq_req = $this->dtr_model->return_data_row_array($tk, $sql_req);
										$numEOA	 = $sq_req['numEOA'];*/
						//end of eoa

						//EOP
						$sql_req = "SELECT count(otforpayment.status) as numEOP, otApp, status FROM otforpayment WHERE otforpayment.emp_id = '$empId' AND
																otforpayment.otDate = '$tempDate' AND (status = 'Approved' OR status = 'Pending')";
						$sq_req  = $this->dtr_model->return_data_row_array($tk, $sql_req);
						$eopStat    = $sq_req['status'];
						$numEOP	 = $sq_req['numEOP'];
						$ot_pay	 = floatval($sq_req['otApp']) * 1;
						//end of EOP

						//ECA
						$sql_req = "SELECT count(coverup_auth.status) as numECA, status
															    FROM coverup_auth WHERE coverup_auth.empId = '$empId' AND
																coverup_auth.cuDate = '$tempDate' AND (status = 'Approved' OR status = 'Pending')";
						$sq_req  = $this->dtr_model->return_data_row_array($tk, $sql_req);
						$ecaStat    = $sq_req['status'];
						$numECA	 = $sq_req['numECA'];
						//end of ECA

						//EUA
						$sql_req = "SELECT count(undertime_auth.status) as numEUA, status FROM undertime_auth WHERE undertime_auth.empId = '$empId' AND
																undertime_auth.dateUndertime = '$tempDate' AND (status = 'Approved' OR status = 'Pending')";
						$sq_req  = $this->dtr_model->return_data_row_array($tk, $sql_req);
						$euaStat    = $sq_req['status'];
						$numEUA	 = $sq_req['numEUA'];
						//end of EUA

						// check if served suspension	
						/*$sql_req = "SELECT sched_suspension.empId FROM sched_suspension WHERE sched_suspension.empId = '$empId' AND
																sched_suspension.dateSched = '$tempDate'";
										$numSUS  = $this->dtr_model->return_num_rows($sql_req);	*/
						// $numSUS  = $this->dtr_model->count_served_suspension($tempDate, $empId)['numSUS'];
						// end of check if served suspension

						$LOA = "";
						if ($numLOA != 0) {
							$span = ($loaStat == 'Approved') ? '<span class="badge bg-success" >LOA</span>' : '<span class="badge bg-warning" >LOA</span>';
							$LOA = '<a href="javascript:;" onclick="viewRequest(\'LOA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server . '\')">' . $span . '</a>';
						}

						$numSIL = 0;
						if ($numLOA != 0) {

							//sil
							$sql_req = "SELECT count(r_l_absence.status) as numSIL, typeLeave, status FROM r_l_absence, rla_day WHERE 
														    r_l_absence.empId = '$empId' AND r_l_absence.empId = rla_day.empId AND rla_day.date = '$tempDate' AND
														    '$tempDate' BETWEEN r_l_absence.incDate_sF AND r_l_absence.incDate_sT AND (status = 'Approved' OR status = 'Pending') AND
														    (if_emer_pers LIKE '%Service Incentive Leave%' OR if_emer_pers LIKE '%SIL%' OR if_emer_pers LIKE '%S.I.L%')";
							$sq_req = $this->dtr_model->return_data_row_array($tk, $sql_req);
							$silStat    = $sq_req['status'];
							$numSIL	= $sq_req['numSIL'];
							//end of sil

							if ($numSIL != 0) {
								$span = ($silStat == 'Approved') ? '<span class="badge bg-success" >SIL</span>' : '<span class="badge bg-warning" >SIL</span>';
								$LOA = '<a href="javascript:;" onclick="viewRequest(\'LOA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server . '\')">' . $span . '</a>';
							}
						}

						$DWA = "";
						if ($numDWA != 0) {
							$span = ($dwaStat == 'Approved') ? '<span class="badge bg-success" >DWA</span>' : '<span class="badge bg-warning" >DWA</span>';
							$DWA = '<a href="javascript:;" onclick="viewRequest(\'DWA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server . '\')">' . $span . '</a>';
						}
						$EOA = "";
						/*if($numEOA != 0){
										$EOA = "EOA";
									}*/
						$EOP = "";
						if ($numEOP != 0) {
							$span = ($eopStat == 'Approved') ? '<span class="badge bg-success" >EOP</span>' : '<span class="badge bg-warning" >EOP</span>';
							$EOP = '<a href="javascript:;" onclick="viewRequest(\'EOP\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server . '\')">' . $span . '</a>';
						}
						$ECA = "";
						if ($numECA != 0) {
							$span = ($ecaStat == 'Approved') ? '<span class="badge bg-success" >ECA</span>' : '<span class="badge bg-warning" >ECA</span>';
							$ECA = '<a href="javascript:;" onclick="viewRequest(\'ECA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server . '\')">' . $span . '</a>';
						}
						$EUA = "";
						if ($numEUA != 0) {
							$span = ($euaStat == 'Approved') ? '<span class="badge bg-success" >EUA</span>' : '<span class="badge bg-warning" >EUA</span>';
							$EUA = '<a href="javascript:;" onclick="viewRequest(\'EUA\',\'' . $empId . '\',\'' . $tempDate . '\',\'' . $server . '\')">' . $span . '</a>';
						}
						$SUS = "";
						/*if($numSUS != 0){
										$SUS = "SS";
									}*/

						$no_schedule = "";
						if (strtolower($promo_type) == "roving") {

							$sql_store = "SELECT noStore FROM promo_mul_sc WHERE empId = '" . $fetch['empId'] . "' AND dateRendered = '" . $tempDate . "' ORDER BY pId DESC";
							$num_store = $this->dtr_model->return_data_row_array($tk, $sql_store);
							$no_store = '';
							if (!empty($num_store)) {

								$no_store = $num_store['noStore'];
							}
							// echo $tempDate."=".$no_store."<br>";
							if ($no_store == "") {

								$sql = "SELECT noStore FROM promo_def_sched WHERE empId = '" . $fetch['empId'] . "' AND day_sched = '" . $day . "' ORDER BY pdId DESC";
								$no_store = $this->dtr_model->return_data_row_array($tk, $sql);
								if (!empty($num_store)) {
									$no_store = $num_store['noStore'];
								}
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

							$sql_do = "SELECT amsId FROM assign_mul_sc WHERE empId = '" . $fetch['empId'] . "' AND
																status = 'active' AND status2 = 'Approved' AND '" . $tempDate . "' BETWEEN dateFrom AND dateTo 
																ORDER BY id DESC";
							@$amsId 	= $this->dtr_model->return_data_row_array($tk, $sql_do)['amsId'];

							$sql_store = "SELECT COUNT(id) AS exist FROM promo_nosched WHERE empId = '" . $fetch['empId'] . "' AND amsId = '" . $amsId . "' AND date = '" . $tempDate . "'";
							$no_sched 	= $this->dtr_model->return_data_row_array($tk, $sql_store)['exist'];

							if ($no_sched == 1) {
								$no_schedule = "true";
							}
						}

						if ($yesHol == "true" && !$numODA) {

							echo "<tr>
													<td>$dateFrom</td>
													<td>$day</td>
													<td colspan='6' align='center'>$nH [ $cat ]</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td align='center'><a href='javascript:viewTime(\"$tk\",\"$empId\",\"$dateFrom\")'><i class='fa fa-eye'></i></a></td>
											</tr>";
						} else if ($show_do == 1) {

							echo "<tr>
													<td>$dateFrom</td>
													<td>$day</td>
													<td colspan='6' align='center' style = 'color:red'>DAY-OFF</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td align='center'><a href='javascript:viewTime(\"$tk\",\"$empId\",\"$dateFrom\")'><i class='fa fa-eye'></i></a></td>
											</tr>";
						} else if ($no_schedule == "true" && !$dutyl) {

							echo "<tr>
													<td>$dateFrom</td>
													<td>$day</td>
													<td colspan='6' align='center' style = 'color:red'>NO SCHEDULE</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td align='center'><a href='javascript:viewTime(\"$tk\",\"$empId\",\"$dateFrom\")'><i class='fa fa-eye'></i></a></td>
											</tr>";
						} else {

							echo '<tr>
												<td>' . $dateFrom . '</td>
												<td>' . $day . '</td>
												<td>' . $in1_store . '' . $in1 . '</td>
												<td>' . $out1_store . '' . $out1 . '</td>
												<td>' . $in2_store . '' . $in2 . '</td>
												<td>' . $out2_store . '' . $out2 . '</td>
												<td>' . $in3_store . '' . $in3 . '</td>
												<td>' . $out3_store . '' . $out3 . '</td>
												<td>' . $hw . '</td>
												<td>' . $newHM . '</td>
												<td>' . $oT . '</td>
												<td>' . $LOA . ' ' . $DWA . ' ' . $EOA . ' ' . $EOP . ' ' . $ECA . ' ' . $EUA . ' ' . $SUS . '</td>';
							echo "
												<td align='center'><a href='javascript:viewTime(\"$tk\",\"$empId\",\"$dateFrom\")'><i class='fa fa-eye'></i></a></td>
											</tr>";
						}

						$dateFrom = date('M. d, Y', strtotime($dateFrom . "+1 day"));
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<?php

	// Previous Cut Off
	$sql = "SELECT * FROM perday_previous WHERE empId = '$empId' AND coDF1 = '" . $fetch['dateFrom'] . "' AND coDF2 = '" . $fetch['dateTo'] . "'";
	$sql2 = "SELECT * FROM perday_previous_allow WHERE empId = '$empId' AND dateFrom = '" . $fetch['dateFrom'] . "' AND dateTo = '" . $fetch['dateTo'] . "'";
	if ($this->dtr_model->return_data_num_rows($tk, $sql) > 0 && $this->dtr_model->return_data_num_rows($tk, $sql2) > 0) { ?>
		<br>
		<div class="row">
			<div class="col-md-12 table-responsive">

				<table class="table table-md table-stripped" width="100%">
					<thead>
						<tr>
							<th colspan="13">Previous Cut Off</th>
						</tr>
						<tr>
							<th>Date</th>
							<th>Day</th>
							<th>IN1</th>
							<th>OUT1</th>
							<th>IN2</th>
							<th>OUT2</th>
							<th>IN3</th>
							<th>OUT3</th>
							<th class="text-center">HW</th>
							<th class="text-center">DW</th>
							<th class="text-center">OT</th>
							<th class="text-center">App. Request</th>
							<th>E</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$dw_addOn = $this->dtr_model->return_data_result_array($tk, $sql);
						foreach ($dw_addOn as $nf) {

							$dateDuty 	= $nf['dateDuty'];
							$dateFrom  	= date('M. d, Y', strtotime($nf['dateDuty']));

							$hw_new 	= floatval($nf['hw_new']);
							$hw_con 	= $nf['hw_con'] + 0;
							$request 	= $nf['request'];
							$dayS 		= date("D", strtotime($dateDuty));
							$dateD 		= date("M. d, Y", strtotime($dateDuty));

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

							echo "<tr>
													<td><span class='date_$dutyId'>$dateD</span></td>
													<td>$dayS</td>
													<td>" . $in1_store . "" . $in1 . "</td>
													<td>" . $out1_store . "" . $out1 . "</td>
													<td>" . $in2_store . "" . $in2 . "</td>
													<td>" . $out2_store . "" . $out2 . "</td>
													<td>" . $in3_store . "" . $in3 . "</td>
													<td>" . $out3_store . "" . $out3 . "</td>
													<td align='center'>$hw_new</td>
													<td align='center'>$hw_con</td>
													<td align='center'>0</td>
													<td align='center'>$request</td>
													<td align='center'><a href='javascript:viewTime(\"$tk\",\"$empId\",\"$dateFrom\")'><i class='fa fa-eye'></i></a></td>
												</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div><?php
			}

			// Previous Cut Off OT
			// $condition = array('emp_id' => $empId, 'supDF' => $input['dateFrom'], 'supDT' => $input['dateTo'], 'otDate <' => $input['dateFrom']);
			// $sql  = $this->masterfile_model->selectAll_tcA_tk($server, 'otforpayment', $condition);
			$query = "SELECT * FROM otforpayment WHERE emp_id='$empId' AND supDF='$fetch[dateFrom]' AND supDT='$fetch[dateTo]' AND otDate < '$fetch[dateFrom]'";
			$sql = $this->dtr_model->return_data_result_array($tk, $query);
			if ($sql) { ?>
		<div class="row">
			<div class="col-md-12 table-responsive">
				<table class="table table-md table-stripped" width="100%">
					<thead>
						<tr>
							<th colspan="13">Previous Cut Off OT</th>
						</tr>
						<tr>
							<th>Date</th>
							<th>Day</th>
							<th>IN1</th>
							<th>OUT1</th>
							<th>IN2</th>
							<th>OUT2</th>
							<th>IN3</th>
							<th>OUT3</th>
							<th class="text-center">HW</th>
							<th class="text-center">DW</th>
							<th class="text-center">OT</th>
							<th class="text-center">App. Request</th>
							<th>E</th>
						</tr>
					</thead>
					<tbody>
						<?php

						foreach ($sql as $nf) {

							$dateDuty   = $nf['otDate'];
							$dateFrom   = date('M. d, Y', strtotime($nf['otDate']));
							$otApp 	    = $nf['otApp'] * 1;
							$dayS       = date("D", strtotime($dateDuty));
							$dateD      = date("M. d, Y", strtotime($dateDuty));

							$condition  = array('bioMetricId' => $bioM, 'date' => $dateDuty);
							// $dtr        = $this->masterfile_model->selectAll_tcR_tk($server, 'duty', $condition);
							$query      = "SELECT * FROM duty WHERE bioMetricId='$bioM' AND date='$dateDuty'";
							$dtr        = $this->dtr_model->return_data_row_array($tk, $query);
							$dutyId     = $dtr['dutyId'];
							$in1        = $dtr['in1'];
							$out1       = $dtr['out1'];

							$in2        = $dtr['in2'];
							$out2       = $dtr['out2'];

							$in3        = $dtr['in3'];
							$out3       = $dtr['out3'];

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

							echo "<tr>
                                        <td><span class='date_$dutyId'>$dateD</span></td>
                                        <td>$dayS</td>
                                        <td>" . $in1_store . "" . $in1 . "</td>
                                        <td>" . $out1_store . "" . $out1 . "</td>
                                        <td>" . $in2_store . "" . $in2 . "</td>
                                        <td>" . $out2_store . "" . $out2 . "</td>
                                        <td>" . $in3_store . "" . $in3 . "</td>
                                        <td>" . $out3_store . "" . $out3 . "</td>
                                        <td align='center'>0</td>
                                        <td align='center'>0</td>
                                        <td align='center'>$otApp</td>
                                        <td align='center'></td>
                                        <td align='center'><a href='javascript:viewTime(\"$tk\",\"$empId\",\"$dateFrom\")'><i class='fa fa-eye'></i></a></td>
                                    </tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	<?php
			}
	?>
	<script type="text/javascript">
		$(document).ready(function() {

			var dataTable = $('#dt_dtrEntry').DataTable({

				"destroy": true,
				"searching": false,
				"info": false,
				"ordering": false,
				"paging": false,
				"scrollY": '40vh',
				"scrollCollapse": true
			});
		});
	</script>
<?php
} else if ($request == "view_schedule") {

	$month 	= $fetch['month'];
	$year 	= $fetch['year'];
	$future_year = ($year * 1) + 10;

	$temp_date 	= date("Y-m-d", strtotime("$year-$month-01"));

	$back_month = date("m", strtotime($temp_date . "-1 month"));
	$back_year 	= date("Y", strtotime($temp_date . "-1 month"));

	$add_month 	= date("m", strtotime($temp_date . "+1 month"));
	$add_year 	= date("Y", strtotime($temp_date . "+1 month"));
?>
	<style type="text/css">
		.tableNo {
			border: 5px solid white;
		}
	</style>
	<div class="table-responsive">
		<table class="table tableNo">
			<tr>
				<td width="22%">&nbsp;</td>
				<td width="3%"><a href="javascript:;" onclick="view_sched2('<?php echo $back_month ?>','<?php echo $back_year ?>')"><i class="fas fa-arrow-left"></i></a></td>
				<td width="15%">
					<select name="month_" class="form-control" onchange="month_n()">
						<?php for ($x = 1; $x <= 12; $x++) : ?>

							<option value="<?php echo $x ?>" <?php if ($month * 1 == $x) : ?> selected <?php endif; ?>><?php echo date("F", strtotime("$year-$x-01")); ?></option>

						<?php endfor; ?>
					</select>
				</td>
				<td width="15%">
					<select name="year_" class="form-control" onchange="year_n()">
						<?php for ($y = 2017; $y <= $future_year; $y++) : ?>

							<option value="<?php echo $y ?>" <?php if ($y == $year) : ?> selected <?php endif; ?>><?php echo $y; ?></option>

						<?php endfor; ?>
					</select>
				</td>
				<td width="5%"><a href="javascript:;" onclick="view_sched2('<?php echo $add_month ?>','<?php echo $add_year ?>')"><i class="fas fa-arrow-right"></i></a></td>
				<td width="20%">&nbsp;</td>
			</tr>
		</table>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive all_sched size-emp">

				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {

			view_sched("<?php echo $month; ?>", "<?php echo $year; ?>");
		});
	</script>
<?php
} else if ($request == "view_sched") {

	$empId 	= $fetch['empId'];
	$month 	= $fetch['month'];
	$year 	= $fetch['year'];
	$server = $fetch['server'];

	$last   = date("t", strtotime("$year-$month-01"));

	if ($server == "colonnade") {

		$pis = "pis_colonnade";
		$tk  = "tk_colonnade";
	} else {

		$pis = "pis";

		if ($server == "talibon") {

			$tk = "tk_talibon";
		} else if ($server == "tubigon") {

			$tk = "tk_tubigon";
		} else {

			$tk = "timekeeping";
		}
	}

	$promo_type = strtolower($this->masterfile_model->get_promo_type($pis, $empId)['promo_type']);

?>
	<table id="dt_schedule" class="table table-hover table-striped" width="100%">
		<thead>
			<tr>
				<th>Date</th>
				<th>Day</th>
				<th>Schedule</th>
				<th>Store</th>
			</tr>
		</thead>
		<tbody>
			<?php

			if ($promo_type == "station") {

				for ($i = 1; $i <= $last; $i++) {

					$wholeDate = date("M, d, Y", strtotime("$year-$month-$i"));
					$n_day     = date("Y-m-d", strtotime("$year-$month-$i"));
					$dayS 	   = date("D", strtotime($n_day));

					echo "<tr>
										<td>$wholeDate</td>
										<td>$dayS</td>
										<td>";

					$count = 0;
					$countDO = 0;
					$ctr = 0;

					$cons = "SELECT amsId, shiftCode FROM assign_mul_sc WHERE empId = '$empId' AND 
													 '$n_day' BETWEEN dateFrom AND dateTo ORDER BY id DESC LIMIT 1";
					$fetch_data = $this->masterfile_model->return_data_result_array($tk, $cons);
					foreach ($fetch_data as $co) {

						$count++;
						$shiftCode = $co['shiftCode'];
						$amsId = $co['amsId'];

						$numDayoff = $this->masterfile_model->dayoff($tk, $empId, $amsId, $n_day);
						if ($numDayoff) {

							$countDO++;
							echo "<font color='red'>DAY-OFF</font>";
						} else {

							$sc = "SELECT * FROM shiftcodes WHERE shiftCode = '$shiftCode'";
							$sg = $this->masterfile_model->return_data_row_array($tk, $sc);

							$fIn = $sg['1stIn'];
							$fOut = $sg['1stOut'];

							$sIn = $sg['2ndIn'];
							$sOut = $sg['2ndOut'];

							// request_new_shift
							$sql 	= "SELECT count(request_new_shift.amsId) AS numSC, request_new_shift.reqBy, request_new_shift.dateReq, request_new_shift.timeReq 
																			   FROM request_new_shift WHERE 
																			   request_new_shift.amsId = '$amsId'";
							$sq  	= $this->masterfile_model->return_data_row_array($tk, $sql);
							$numSC 	= $sq['numSC'];
							if ($numSC != 0) {

								// get name
								$sql_ = "SELECT employee3.name FROM employee3 WHERE employee3.emp_id = '" . $sq['reqBy'] . "'";
								$sq_  = $this->masterfile_model->return_data_row_array($pis, $sql_);
								$name = ucwords(strtolower($sq_['name']));

								$dateReq = date("Y/m/d", strtotime($sq['dateReq']));
								$timeReq = date("h:i A", strtotime($sq['timeReq']));
								echo "[ <span data-html='true' data-toggle='tooltip' data-placement='top' title = '$dateReq - $timeReq'><font color = 'blue'>$shiftCode</font> = <sup>$fIn - $fOut , $sIn   $sOut</sup></span> ]";
							}

							// req_new_shift_emp
							$sql 	= "SELECT count(req_new_shift_emp.amsId) AS numSC, req_new_shift_emp.reqBy, req_new_shift_emp.dateReq, req_new_shift_emp.timeReq 
																			   FROM req_new_shift_emp WHERE 
																			   req_new_shift_emp.amsId = '$amsId'";
							$sq  	= $this->masterfile_model->return_data_row_array($tk, $sql);
							$numSC 	= $sq['numSC'];

							if ($numSC != 0) {

								// get name
								$sql_ = "SELECT employee3.name FROM employee3 WHERE employee3.emp_id = '" . $sq['reqBy'] . "'";
								$sq_  = $this->masterfile_model->return_data_row_array($pis, $sql_);
								$name = ucwords(strtolower($sq_['name']));

								$dateReq = date("Y/m/d", strtotime($sq['dateReq']));
								$timeReq = date("h:i A", strtotime($sq['timeReq']));
								echo "[ <span data-html='true' data-toggle='tooltip' data-placement='top' title = '$dateReq - $timeReq [ $name ]'><font color = 'blue'>$shiftCode</font> = <sup>$fIn - $fOut , $sIn   $sOut</sup></span> ]";
							}

							// request_odaf
							$sql 	= "SELECT count(request_odaf.amsId) AS numODA, request_odaf.reqBy, request_odaf.dateReq, request_odaf.timeReq  
																			   FROM request_odaf WHERE 
																			   request_odaf.amsId = '$amsId'";
							$sq  	= $this->masterfile_model->return_data_row_array($tk, $sql);
							$numODA 	= $sq['numODA'];

							if ($numODA != 0) {

								// get name
								$sql_ = "SELECT employee3.name FROM employee3 WHERE employee3.emp_id = '" . $sq['reqBy'] . "'";
								$sq_  = $this->masterfile_model->return_data_row_array($pis, $sql_);
								$name = ucwords(strtolower($sq_['name']));

								$dateReq = date("Y/m/d", strtotime($sq['dateReq']));
								$timeReq = date("h:i A", strtotime($sq['timeReq']));
								echo "[ <span data-html='true' data-toggle='tooltip' data-placement='top' title = '$dateReq - $timeReq <br>[ $name ]'><font color = 'red'>$shiftCode </font> = <sup>$fIn - $fOut , $sIn   $sOut</sup></span> ]";
							}
						}
					}

					if ($count == 0) {

						$adlaw = date('l', strtotime($n_day));
						$numDayoff = $this->masterfile_model->default_DO($tk, $empId, $adlaw);

						if ($numDayoff) {

							$countDO++;
							echo "<font color='red'>DAY-OFF</font>";
						} else {

							$shiftCode = $this->masterfile_model->assign($tk, $empId)['shiftcode'];
							$sc = "SELECT * FROM shiftcodes WHERE shiftCode = '$shiftCode'";
							$shifts = $this->masterfile_model->return_data_result_array($tk, $sc);

							foreach ($shifts as $sg) {

								$ctr++;
								$fIn = $sg['1stIn'];
								$fOut = $sg['1stOut'];

								$sIn = $sg['2ndIn'];
								$sOut = $sg['2ndOut'];

								echo "[ <span data-html='true' data-toggle='tooltip' data-placement='top' title = 'default schedule'><font color = ''>$shiftCode</font> = <sup>$fIn - $fOut , $sIn   $sOut</sup></span> ]";
							}

							if ($ctr == 0) {

								echo "";
							}
						}
					}

					echo "</td>
										<td>";

					if ($countDO > 0) {

						echo "";
					} else {

						// get store name
						$storeName = "";
						$ctr = 0;
						$bunit = $this->masterfile_model->store($pis);
						foreach ($bunit as $str) {

							$promo = "SELECT promo_id FROM promo_record WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'";
							if ($this->masterfile_model->return_data_num_rows($pis, $promo) > 0) {
								$ctr++;

								if ($ctr == 1) {

									$storeName = $str['bunit_name'];
								} else {

									$storeName .= ", " . $str['bunit_name'];
								}
							}
						}

						echo ucwords(strtolower($storeName));
					}
					echo "</td>
									</tr>";
				}
			} else {

				for ($i = 1; $i <= $last; $i++) {

					$wholeDate = date("M, d, Y", strtotime("$year-$month-$i"));
					$n_day     = date("Y-m-d", strtotime("$year-$month-$i"));
					$dayS 	   = date("D", strtotime($n_day));

					echo "<tr>
										<td>$wholeDate</td>
										<td>$dayS</td>
										<td>";

					$count = 0;
					$countDO = 0;
					$ctr = 0;

					$cons = "SELECT amsId, noStore, store1, store2, shiftCode FROM promo_mul_sc WHERE empId = '$empId' AND 
													 dateRendered = '$n_day' ORDER BY pId DESC LIMIT 1";
					$fetch_data = $this->masterfile_model->return_data_result_array($tk, $cons);
					foreach ($fetch_data as $co) {

						$count++;
						$shiftCode 	= $co['shiftCode'];
						$amsId 	 	= $co['amsId'];
						$no_store 	= $co['noStore'];
						$store1 	= $co['store1'];
						$store2 	= $co['store2'];

						$numDayoff = $this->masterfile_model->dayoff($tk, $empId, $amsId, $n_day);
						if ($numDayoff) {

							$countDO++;
							echo "<font color='red'>DAY-OFF</font>";
						} else {

							$sc = "SELECT * FROM shiftcodes WHERE shiftCode = '$shiftCode'";
							$sg = $this->masterfile_model->return_data_row_array($tk, $sc);

							$fIn = $sg['1stIn'];
							$fOut = $sg['1stOut'];

							$sIn = $sg['2ndIn'];
							$sOut = $sg['2ndOut'];

							// request_new_shift
							$sql 	= "SELECT count(request_new_shift.amsId) AS numSC, request_new_shift.reqBy, request_new_shift.dateReq, request_new_shift.timeReq 
																			   FROM request_new_shift WHERE 
																			   request_new_shift.amsId = '$amsId'";
							$sq  	= $this->masterfile_model->return_data_row_array($tk, $sql);
							$numSC 	= $sq['numSC'];
							if ($numSC != 0) {

								// get name
								$sql_ = "SELECT employee3.name FROM employee3 WHERE employee3.emp_id = '" . $sq['reqBy'] . "'";
								$sq_  = $this->masterfile_model->return_data_row_array($pis, $sql_);
								$name = ucwords(strtolower($sq_['name']));

								$dateReq = date("Y/m/d", strtotime($sq['dateReq']));
								$timeReq = date("h:i A", strtotime($sq['timeReq']));
								echo "[ <span data-html='true' data-toggle='tooltip' data-placement='top' title = '$dateReq - $timeReq <br>[ $name ]'><font color = 'blue'>$shiftCode</font> = <sup>$fIn - $fOut , $sIn   $sOut</sup></span> ]";
							}

							// request_odaf
							$sql 	= "SELECT count(request_odaf.amsId) AS numODA, request_odaf.reqBy, request_odaf.dateReq, request_odaf.timeReq  
																			   FROM request_odaf WHERE 
																			   request_odaf.amsId = '$amsId'";
							$sq  	= $this->masterfile_model->return_data_row_array($tk, $sql);
							$numODA 	= $sq['numODA'];

							if ($numODA != 0) {

								// get name
								$sql_ = "SELECT employee3.name FROM employee3 WHERE employee3.emp_id = '" . $sq['reqBy'] . "'";
								$sq_  = $this->masterfile_model->return_data_row_array($pis, $sql_);
								$name = ucwords(strtolower($sq_['name']));

								$dateReq = date("Y/m/d", strtotime($sq['dateReq']));
								$timeReq = date("h:i A", strtotime($sq['timeReq']));
								echo "[ <span data-html='true' data-toggle='tooltip' data-placement='top' title = '$dateReq - $timeReq <br>[ $name ]'><font color = 'red'>$shiftCode </font> = <sup>$fIn - $fOut , $sIn   $sOut</sup></span> ]";
							}
						}
					}

					if ($count == 0) {

						$adlaw = date('l', strtotime($n_day));

						$ds = $this->masterfile_model->promo_def_sched($tk, $empId, $adlaw);
						$shiftCode = $ds['shiftCode'];
						$no_store = $ds['noStore'];

						if ($no_store == '0') {

							echo "<font color='red'>DAY-OFF</font>";
						} else {

							$sc = "SELECT * FROM shiftcodes WHERE shiftCode = '$shiftCode'";
							$shifts = $this->masterfile_model->return_data_result_array($tk, $sc);

							foreach ($shifts as $sg) {

								$ctr++;
								$fIn = $sg['1stIn'];
								$fOut = $sg['1stOut'];

								$sIn = $sg['2ndIn'];
								$sOut = $sg['2ndOut'];

								echo "[ <span data-html='true' data-toggle='tooltip' data-placement='top' title = 'default schedule'><font color = ''>$shiftCode</font> = <sup>$fIn - $fOut , $sIn   $sOut</sup></span> ]";
							}

							if ($ctr == 0) {

								echo "";
							}
						}
					}

					echo "</td>
										<td>";

					$cons = "SELECT noStore, store1, store2 FROM promo_mul_sc WHERE empId = '$empId' AND 
													 dateRendered = '$n_day' ORDER BY pId DESC LIMIT 1";
					$fetch_data = $this->masterfile_model->return_data_result_array($tk, $cons);
					foreach ($fetch_data as $co) {

						$count++;
						if ($co['noStore'] > 0) {

							if ($co['noStore'] == '1') {

								$store = $this->masterfile_model->store_name($tk, $co['store1'])['store_name'];
								echo $store;
							} else {

								$store1 = $this->masterfile_model->store_name($tk, $co['store1'])['store_name'];
								$store2 = $this->masterfile_model->store_name($tk, $co['store2'])['store_name'];

								echo "$store1 / $store2";
							}
						} else {

							echo "";
						}
					}

					if ($count == 0) {

						$adlaw = date('l', strtotime($n_day));
						$ds = $this->masterfile_model->promo_def_sched($tk, $empId, $adlaw);
						$no_store = $ds['noStore'];

						if ($ds['noStore'] > 0) {

							if ($ds['noStore'] == '1') {

								$store = $this->masterfile_model->store_name($tk, $ds['store1'])['store_name'];
								echo $store;
							} else {

								$store1 = $this->masterfile_model->store_name($tk, $ds['store1'])['store_name'];
								$store2 = $this->masterfile_model->store_name($tk, $ds['store2'])['store_name'];

								echo "$store1 / $store2";
							}
						} else {

							echo "";
						}
					}

					echo "</td>
									</tr>";
				}
			}
			?>
		</tbody>
	</table>
	<script type="text/javascript">
		$(document).ready(function() {

			var dt_schedule = $('#dt_schedule').DataTable({

				"destroy": true,
				"autoWidth": true,
				"searching": false,
				"info": false,
				"ordering": false,
				"paging": false,
				"scrollY": '45vh',
				"scrollCollapse": true
			});

			$('[data-toggle="tooltip"]').tooltip()
		});
	</script>
<?php
} else if ($request == "basic_info") {

	$row = $this->account_model->company_basic_info($userId);

	if ($row['photo'] == "") {

		$photo = "assets/img/user/user-1.png";
	} else {

		$photo = $row['photo'];
	}

?>
	<form id="data_profile" action="" method="POST" data-parsley-validate="true">
		<label class="title">Profile</label>
		<div class="modf"><label class="title">Basic Information</label>
			<button name="edit" id="edit-basicinfo" class="btn btn-primary btn-xs" type="button">edit</button>
			<button class="btn btn-primary btn-sm" id="update-basicinfo" style="display:none" type="submit">update</button>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-4">
				<center>
					<div class="card" style="width: 18rem;">
						<img class="card-img-top" src="<?php echo base_url($photo); ?>" alt="Card image cap">
						<div class="card-body">
							<h5 class="card-title"><?php echo $supplier_name; ?></h5>
							<p class="card-text">Agency</p>
							<a href="javascript:;" id="change_photo" class="btn btn_image btn-sm btn-success">Change Photo</a>
						</div>
					</div>
				</center>
			</div>
			<div class="col-md-8">
				<div class="form-horizontal">
					<div class="container">
						<div class="row">
							<input type="hidden" name="userId" value="<?php echo $userId; ?>">
							<div class="form-group form-group-sm col-sm-12">
								<div class="row">
									<label for="address" class="col-sm-3 col-form-label">Address</label>
									<div class="col-sm-9">
										<input type="text" class="form-control profile_field" id="address" name="address" value="<?php echo $row['address']; ?>" disabled="">
									</div>
								</div>
							</div>
							<div class="form-group form-group-sm col-sm-12">
								<div class="row">
									<label for="email" class="col-sm-3 col-form-label">Email Address</label>
									<div class="col-sm-9">
										<input type="email" class="form-control profile_field" id="email" name="email" value="<?php echo $row['email_address']; ?>" disabled="">
									</div>
								</div>
							</div>
							<div class="form-group form-group-sm col-sm-12">
								<div class="row">
									<label for="telephone_no" class="col-sm-3 col-form-label">Tel No.</label>
									<div class="col-sm-9">
										<input type="text" class="form-control profile_field" id="telephone_no" name="telephone_no" value="<?php echo $row['telephone']; ?>" disabled="">
									</div>
								</div>
							</div>
							<div class="form-group form-group-sm col-sm-12">
								<div class="row">
									<label for="cellphone_no" class="col-sm-3 col-form-label">Cell No.</label>
									<div class="col-sm-9">
										<input type="text" class="form-control profile_field" id="cellphone_no" name="cellphone_no" value="<?php echo $row['cellphone']; ?>" disabled="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script src="<?php echo base_url('assets/plugins/parsley/dist/parsley.js'); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$("button#edit-basicinfo").click(function() {

				$("button#update-basicinfo").show();
				$("button#edit-basicinfo").hide();
				$("input.profile_field").prop("disabled", false);
			});

			$("a#change_photo").click(function(e) {

				e.preventDefault();

				$("#profilePic").modal({
					backdrop: 'static',
					keyboard: false
				});

				$("#profilePic").modal("show");
				var userId = $("input[name = 'userId']").val();

				$.ajax({
					type: "POST",
					url: "<?php echo site_url('changeProfilePic'); ?>",
					data: {
						userId: userId
					},
					success: function(data) {

						$(".profilePic").html(data);
					}
				});
			});

			$("form#data_profile").submit(function(e) {

				e.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: "<?php echo site_url('submit_profile'); ?>",
					type: 'POST',
					data: formData,
					success: function(data) {

						var response = data.trim();
						if (response == "success") {

							swal({
									title: "Updated!",
									text: "Basic Information successfully updated!",
									type: "success",
									confirmButtonClass: 'btn-success',
									confirmButtonText: 'Success',
									closeOnConfirm: false
								},
								function(isConfirm) {
									if (isConfirm) {

										location.reload();
									}
								});
						} else if (response == "empty") {

							swal({
								title: "Opps!",
								text: "Nothing to update",
								type: "warning",
								confirmButtonClass: 'btn-warning',
								confirmButtonText: 'Confirm!'
							});
						} else {

							alert(response);
						}
					},
					cache: false,
					contentType: false,
					processData: false
				});
			});
		});
	</script>
<?php
} else if ($request == "changeProfilePic") {

?>
	<small><i><span class="text-danger">Note:</span> Acceptable file format are [ jpg, png ] and file size should not be greater than 2MB.</i></small><br><br>
	<input type="hidden" name="userId" value="<?php echo $userId; ?>">

	<div class="row justify-content-md-center">
		<div class="col col-md-8">
			<center>

				<img id="photoprofile" class="rounded-circle center profilePhoto"><br><br>
				<input type='button' id='profile_change' style='display:none;' class='btn btn-primary btn-md btn-block' value='Change Photo?' onclick='changePhoto("Photo","profile","profile_change")'>
				<input type='file' name='profile' id='profile' class='btn btn-default btn-block' onchange='readURL(this,"profile");'>
				<input type='button' name='clearprofile' id='clearprofile' style='display:none' class='btn btn-warning btn-block' value='Clear' onclick="clears('profile','photoprofile','clearprofile')">
			</center>
		</div>
	</div>

	<script type="text/javascript">
		var userId = $("[name = 'userId']").val();

		$('#photoprofile').removeAttr('src');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('getProfilePic'); ?>",
			data: {
				userId: userId
			},
			success: function(data) {

				if (data != '') {
					document.getElementById("photoprofile").src = "<?php echo base_url(); ?>" + data;
					$('#profile').hide();
					$("#profile_change").show();
				} else {
					$("#profile_change").hide();
					$('#profile').show();
				}
			}
		});
	</script>
<?php
} else if ($request == "change_password") {

?>
	<style type="text/css">
		.field-icon {
			float: right;
			margin-left: -40px;
			margin-top: -25px;
			padding-right: 25px;
			position: relative;
			z-index: 2;
		}
	</style>
	<label class="title">Change Password</label>
	<hr>
	<div class="alert alert-info fade in" style="border-left: 4px solid; border-left-color: currentcolor;">
		<strong>Reminder!</strong> Password must contain (UpperCase, LowerCase, Number/SpecialChar and minimum of 6 characters.)
	</div>
	<form class="form-horizontal" id="data_password" action="" method="POST" data-parsley-validate="true">
		<div class="container">
			<div class="row">
				<input type="hidden" name="userId" value="<?php echo $userId; ?>">
				<div class="form-group form-group-sm col-sm-12">
					<div class="row">
						<label for="current_pass" class="col-sm-3 col-form-label">Current Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="current_pass" name="current_pass"><span toggle="#current_pass" class="fa fa-fw fa-eye field-icon toggle-password" data-toggle="tooltip" title="Show password"></span>
						</div>
					</div>
				</div>
				<div class="form-group form-group-sm col-sm-12">
					<div class="row">
						<label for="new_pass" class="col-sm-3 col-form-label">New Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="new_pass" name="new_pass" data-parsley-required="true" data-parsley-pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"><span toggle="#new_pass" class="fa fa-fw fa-eye field-icon toggle-password" data-toggle="tooltip" title="Show password"></span>
						</div>
					</div>
				</div>
				<div class="form-group form-group-sm col-sm-12">
					<div class="row">
						<label for="confirm_pass" class="col-sm-3 col-form-label">Confirm Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="confirm_pass" name="confirm_pass" data-parsley-required="true" data-parsley-pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"><span toggle="#confirm_pass" class="fa fa-fw fa-eye field-icon toggle-password" data-toggle="tooltip" title="Show password"></span>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<button type="submit" id="confirm_btn" class="btn btn-primary btn-block col-md-4 md-offset-4">Confirm</button>
		</div>
	</form>
	<script src="<?php echo base_url('assets/plugins/parsley/dist/parsley.js'); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-toggle="tooltip"]').tooltip();

			$(".toggle-password").click(function() {

				$(this).toggleClass("fa-eye fa-eye-slash");

				var input = $($(this).attr("toggle"));
				if (input.attr("type") == "password") {

					input.attr("type", "text");
					$(this).attr('data-original-title', 'Hide password');
				} else {
					input.attr("type", "password");
					$(this).attr('data-original-title', 'Show password');
				}
			});

			$("form#data_password").submit(function(e) {

				e.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: "<?php echo site_url('submit_password'); ?>",
					type: 'POST',
					data: formData,
					success: function(data) {

						var response = data.trim();
						if (response == "success") {

							swal({
									title: "Updated!",
									text: "Password was successfully updated!",
									type: "success",
									confirmButtonClass: 'btn-success',
									confirmButtonText: 'Success',
									closeOnConfirm: false
								},
								function(isConfirm) {
									if (isConfirm) {

										location.reload();
									}
								});

						} else if (response == "incorrect_password") {

							swal({
									title: "Opps!",
									text: "Incorrect Password!",
									type: "error",
									confirmButtonClass: 'btn-danger',
									confirmButtonText: 'Confirm',
									closeOnConfirm: false
								},
								function(isConfirm) {
									if (isConfirm) {

										swal.close();
									}
								});

						} else if (response == "unmatched") {

							swal({
									title: "Opps!",
									text: "New Password and Confirm Password did not match!",
									type: "error",
									confirmButtonClass: 'btn-danger',
									confirmButtonText: 'Confirm',
									closeOnConfirm: false
								},
								function(isConfirm) {
									if (isConfirm) {

										swal.close();
									}
								});

						} else {

							alert(response);
						}
					},
					cache: false,
					contentType: false,
					processData: false
				});
			});
		});
	</script>
<?php
} else if ($request == "change_username") {

?>
	<label class="title">Change Username</label>
	<hr>
	<div class="alert alert-info fade in" style="border-left: 4px solid; border-left-color: currentcolor;">
		<strong>Reminder!</strong> Username should be greater than or equal to 5 characters.
	</div>
	<form class="form-horizontal" id="data_username" action="" method="POST" data-parsley-validate="true">
		<div class="container">
			<div class="row">
				<input type="hidden" name="userId" value="<?php echo $userId; ?>">
				<div class="form-group form-group-sm col-sm-12">
					<div class="row">
						<label for="current_username" class="col-sm-3 col-form-label">Current Username</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="current_username" name="current_username" data-parsley-required="true">
						</div>
					</div>
				</div>
				<div class="form-group form-group-sm col-sm-12">
					<div class="row">
						<label for="new_username" class="col-sm-3 col-form-label">New Username</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="new_username" name="new_username" data-parsley-required="true">
						</div>
					</div>
				</div>
				<div class="form-group form-group-sm col-sm-12">
					<div class="row">
						<label for="confirm_username" class="col-sm-3 col-form-label">Confirm Username</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="confirm_username" name="confirm_username" data-parsley-required="true">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<button type="submit" class="btn btn-primary btn-block col-md-4 md-offset-4">Confirm</button>
		</div>
	</form>
	<script src="<?php echo base_url('assets/plugins/parsley/dist/parsley.js'); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$("form#data_username").submit(function(e) {

				e.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: "<?php echo site_url('submit_username'); ?>",
					type: 'POST',
					data: formData,
					success: function(data) {

						var response = data.trim();
						if (response == "success") {

							swal({
									title: "Updated!",
									text: "Username was successfully updated!",
									type: "success",
									confirmButtonClass: 'btn-success',
									confirmButtonText: 'Success',
									closeOnConfirm: false
								},
								function(isConfirm) {
									if (isConfirm) {

										location.reload();
									}
								});

						} else if (response == "incorrect_username") {

							swal({
									title: "Opps!",
									text: "Incorrect Username!",
									type: "error",
									confirmButtonClass: 'btn-danger',
									confirmButtonText: 'Confirm',
									closeOnConfirm: false
								},
								function(isConfirm) {
									if (isConfirm) {

										swal.close();
									}
								});

						} else if (response == "unmatched") {

							swal({
									title: "Opps!",
									text: "New Username and Confirm Username did not match!",
									type: "error",
									confirmButtonClass: 'btn-danger',
									confirmButtonText: 'Confirm',
									closeOnConfirm: false
								},
								function(isConfirm) {
									if (isConfirm) {

										swal.close();
									}
								});

						} else {

							alert(response);
						}
					},
					cache: false,
					contentType: false,
					processData: false
				});
			});
		});
	</script>
<?php
} else if ($request == "vTEntry") {

	$tk = $fetch['server'];
	$empId = $fetch['empId'];
	$date  = $fetch['dateRendered'];
	$nDate = date('m-d-Y', strtotime($date));
	$df = date('Y-m-d', strtotime($date));
	echo    '<table class="table table-sm table-bordered">';
	echo    '<tr>
				<th>Shift Code</th>
				<th>Time</th>
            </tr>';
	$cond = "empId='$empId' AND ('$df' BETWEEN dateFrom AND dateTo) ORDER BY amsId DESC LIMIT 1";
	// $query = $this->masterfile_model->selectAll_tcA_tk($server, 'assign_mul_sc', $cond);


	$assign = "SELECT * FROM assign_mul_sc WHERE $cond";
	$query = $this->dtr_model->return_data_result_array($tk, $assign);
	foreach ($query as $c) {
		$amsId  = $c['amsId'];
		$mSC    = $c['shiftCode'];
		$dF     = $c['dateFrom'];
		$dT     = $c['dateTo'];
		$status = $c['status'];
		$cond   = "shiftCode = '$mSC'";
		// $e      = $this->masterfile_model->selectAll_tcR_tk($server, 'shiftcodes', $cond);

		$scodes = "SELECT * FROM shiftcodes WHERE $cond";
		$e = $this->dtr_model->return_data_row_array($tk, $scodes);

		$in1    = $e['1stIn'];
		$out1   = $e['1stOut'];
		$in2    = $e['2ndIn'];
		$out2   = $e['2ndOut'];
		$reqStat = '';
		$dateReq = '';
		$cond = "amsId = '$amsId' LIMIT 1";
		// $odaf = $this->masterfile_model->selectAll_tcR_tk($server, 'request_odaf', $cond);
		$oda = "SELECT * FROM request_odaf WHERE $cond";
		$odaf = $this->dtr_model->return_data_row_array($tk, $oda);
		if ($odaf) {
			$dateReq = $odaf['dateReq'];
			$timeReq = $odaf['timeReq'];
			$dateReq = date("Y/m/d g:i A", strtotime("$dateReq $timeReq"));
			$reqStat = "<b>O.D.A.</b>";
		}
		$cond   = "amsId = '$amsId' LIMIT 1";
		// $rns    = $this->masterfile_model->selectAll_tcR_tk($server, 'request_new_shift', $cond);
		$rn = "SELECT * FROM request_new_shift WHERE $cond";
		$rns = $this->dtr_model->return_data_row_array($tk, $rn);
		if ($rns) {
			$dateReq    = $rns['dateReq'];
			$timeReq    = $rns['timeReq'];
			$dateReq    = date("Y/m/d g:i A", strtotime("$dateReq $timeReq"));
			$reqStat    = "<b>C.S.</b>";
		}
		if ($status == 'active') {
			$color = 'text-success';
		} else {
			$color = 'text-warning';
		}
		echo    '<tr>
                    <td>' . $mSC . '</td>
                    <td>' . $in1 . ' - ' . $out1 . ' | ' . $in2 . ' - ' . $out2 . ' </td>
                </tr>';
	}
	echo    '</table>';



	echo "<table class = 'table table-sm table-striped'>
				<thead>
					<tr>
						<th>Date</th>
						<th>Time</th>
						<th>Mode</th>
						<th>Terminal</th>
						<th>Store</th>
					</tr>
				</thead>
				<tbody>";

	$bio = "";
	$query = "";

	$assign = "SELECT empId, allowId, bioMetricId, barcodeId FROM assign WHERE empId = '$empId'";
	$g = $this->dtr_model->return_data_row_array($tk, $assign);

	$allowId = $g['allowId'];
	if ($allowId == "Biometric ID") {


		$bio = $g['bioMetricId'];
		$query = "SELECT * FROM duty_biometric WHERE bioMetricId = '$bio' AND date = '$nDate' 
									ORDER BY ADDTIME(timeIn,timeOut) ASC";
	}

	if ($allowId == "Logbox ID") {

		$bio = $g['barcodeId'];
		$query = "SELECT * FROM duty_logbox WHERE bioMetricId = '$bio' AND date = '$nDate' 
									ORDER BY ADDTIME(timeIn,timeOut) ASC";
	}

	$con = $this->dtr_model->return_data_result_array($tk, $query);
	foreach ($con as $c) {

		$bioM 		= $c['bioMetricId'];
		$date 		= $c['date'];
		$mode 		= $c['mode'];
		$timeIn 	= $c['timeIn'];
		$timeOut 	= $c['timeOut'];

		$exp = explode("-", $date);
		$fromDate = "$exp[2]-$exp[0]-$exp[1]";

		$sql_store = "SELECT store FROM duty_logbox WHERE bioMetricId = '$bioM' AND dateDuty = '$fromDate' AND timeIn = '$timeIn' AND timeOut = '$timeOut'";
		$store = $this->dtr_model->return_data_row_array($tk, $sql_store)['store'];

		if ("$timeOut" == "0") {
			$time = $timeIn;
		}

		if ("$timeIn" == "0") {
			$time = $timeOut;
		}

		echo "
							<tr>
								<td>$date</td>
								<td>$time</td>
								<td>$mode</td>
								<td>$allowId</td>
								<td>$store</td>
							</tr>	
						";
	}

	echo 	"</tbody>
			</table>";
} else if ($request == "emp_violation_list") {
?>

	<input type="hidden" name="cutoff" value="<?php echo $fetch['cutoff']; ?>">
	<input type="hidden" name="dateFrom" value="<?php echo $fetch['dateFrom']; ?>">
	<input type="hidden" name="dateTo" value="<?php echo $fetch['dateTo']; ?>">

	<div class="checkout">
		<div class="checkout-header">
			<div class="row">
				<?php if (isset($alturas)) { ?>
					<div class="col-md-3 col-sm-3">
						<div class="step active step_tagbilaran">
							<a href="javascript:void(0);" id="tagbilaran" class="server">
								<div class="number">1</div>
								<div class="info">
									<div class="title">Tagbilaran, Bohol</div>
									<div class="desc">Alturas Mall, ICM , Plaza Marcela & Alta Citta</div>
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="step step_tubigon_">
							<!-- <a href="javascript:void(0);" id="tubigon" class="server_"> -->
							<div class="number">2</div>
							<div class="info">
								<div class="title">Tubigon, Bohol</div>
								<div class="desc">Alturas Tubigon</div>
							</div>
							<!-- </a> -->
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="step step_talibon">
							<a href="javascript:void(0);" id="talibon" class="server">
								<div class="number">3</div>
								<div class="info">
									<div class="title">Talibon, Bohol</div>
									<div class="desc">Alturas Talibon</div>
								</div>
							</a>
						</div>
					</div>
				<?php } else { ?>
					<div class="col-md-3 col-sm-3">
						<div class="step active step_tagbilaran">
							<div class="number">1</div>
							<div class="info">
								<div class="title">Tagbilaran, Bohol</div>
								<div class="desc">Alturas Mall, ICM , Plaza Marcela & Alta Citta</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="step step_tubigon_">
							<div class="number">2</div>
							<div class="info">
								<div class="title">Tubigon, Bohol</div>
								<div class="desc">Alturas Tubigon</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="step step_talibon">
							<div class="number">3</div>
							<div class="info">
								<div class="title">Talibon, Bohol</div>
								<div class="desc">Alturas Talibon</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="col-md-3 col-sm-3">
					<div class="step step_colonnade">
						<?php if (isset($colonnade)) { ?>
							<a href="javascript:void(0);" id="colonnade" class="server">
								<div class="number">4</div>
								<div class="info">
									<div class="title">Cebu</div>
									<div class="desc">Colonnade Colon & Mandaue</div>
								</div>
							</a>
						<?php } else { ?>
							<div class="number">4</div>
							<div class="info">
								<div class="title">Cebu</div>
								<div class="desc">Colonnade Colon & Mandaue</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="checkout-body">
			<input type="hidden" name="server" value="tagbilaran">
			<div class="table-responsive">
				<table id="dt_emp_violation_list" class="table table-bordered table-hover" width="100%">
					<thead>
						<tr>
							<th> Fullname</th>
							<th> Company</th>
							<th> BusinessUnit</th>
							<th> Position</th>
							<th> PromoType</th>
							<th> Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {

			var cutoff = $("input[name = 'cutoff']").val();
			var server = $("input[name = 'server']").val();
			var dateFrom = $("input[name = 'dateFrom']").val();
			var dateTo = $("input[name = 'dateTo']").val();
			employee_list(cutoff, server, dateFrom, dateTo);

			$("a.server").click(function(e) {

				e.preventDefault();
				var id = $(this).attr('id');

				$("div.step").removeClass('active');
				$("div.step_" + id).addClass('active');
				$("input[name = 'server']").val(id.trim());

				employee_list(cutoff, id, dateFrom, dateTo);
			});
		});
	</script>
<?php
} else if ($request == "company_list") {

?>

	<input type="hidden" name="cutoff" value="<?php echo $fetch['cutoff']; ?>">
	<input type="hidden" name="dateFrom" value="<?php echo $fetch['dateFrom']; ?>">
	<input type="hidden" name="dateTo" value="<?php echo $fetch['dateTo']; ?>">

	<div class="checkout">
		<div class="checkout-header">
			<div class="row">
				<?php if (isset($alturas)) { ?>
					<div class="col-md-6 col-sm-6">
						<div class="step active step_tagbilaran">
							<a href="javascript:void(0);" id="tagbilaran" class="server">
								<div class="number">1</div>
								<div class="info">
									<div class="title">Bohol</div>
									<div class="desc">Alturas Mall, ICM , Plaza Marcela, Alta Citta, Alturas Tubigon & Alturas Talibon</div>
								</div>
							</a>
						</div>
					</div>
				<?php } else { ?>
					<div class="col-md-6 col-sm-6">
						<div class="step active step_tagbilaran">
							<div class="number">1</div>
							<div class="info">
								<div class="title">Bohol</div>
								<div class="desc">Alturas Mall, ICM , Plaza Marcela, Alta Citta, Alturas Tubigon & Alturas Talibon</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="col-md-6 col-sm-6">
					<div class="step step_colonnade">
						<?php if (isset($colonnade)) { ?>
							<a href="javascript:void(0);" id="colonnade" class="server">
								<div class="number">2</div>
								<div class="info">
									<div class="title">Cebu</div>
									<div class="desc">Colonnade Colon & Mandaue</div>
								</div>
							</a>
						<?php } else { ?>
							<div class="number">2</div>
							<div class="info">
								<div class="title">Cebu</div>
								<div class="desc">Colonnade Colon & Mandaue</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="checkout-body">
			<input type="hidden" name="server" value="tagbilaran">
			<div class="table-responsive">
				<table id="dt_sup_violation_list" class="table table-bordered table-hover" width="100%">
					<thead>
						<tr>
							<th> Company</th>
							<th> Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {

			var cutoff = $("input[name = 'cutoff']").val();
			var server = $("input[name = 'server']").val();
			var dateFrom = $("input[name = 'dateFrom']").val();
			var dateTo = $("input[name = 'dateTo']").val();
			company_list(cutoff, server, dateFrom, dateTo);

			$("a.server").click(function(e) {

				e.preventDefault();
				var id = $(this).attr('id');

				$("div.step").removeClass('active');
				$("div.step_" + id).addClass('active');
				$("input[name = 'server']").val(id.trim());

				company_list(cutoff, id, dateFrom, dateTo);
			});
		});
	</script>
<?php
} else if ($request == "view_company_violation") {

?>
	<input type="hidden" name="id" value="<?= $fetch['id'] ?>">
	<input type="hidden" name="dateFrom" value="<?= $fetch['dateFrom'] ?>">
	<input type="hidden" name="dateTo" value="<?= $fetch['dateTo'] ?>">
	<input type="hidden" name="server" value="<?= $fetch['server'] ?>">
	<div class="row">
		<div class="col-md-9 violation_uploaded">
			<label>Date Uploaded: <strong><?= date('F d, Y h:i A', strtotime($violation[0]->created_at)); ?></strong></label>
		</div>
		<div class="col-md-3 d-flex text-right">
			<label class="mr-1 pt-2">Page</label>
			<select name="violation_id" class="form-control" size="1" onchange="view_image(this.value)">
				<?php
				$i = 1;
				foreach ($violation as $v) {
					echo "<option value='" . $v->id . "'>" . $i . "</option>";
					$i++;
				}
				?>
			</select>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-12 violation">
			<img src="<?= base_url() . 'admins/' . $violation[0]->image_path ?>" width="100%" loading="lazy">
		</div>
	</div>
<?php
} else if ($request == "view_violation") {
?>
	<input type="hidden" name="empId" value="<?= $fetch['empId'] ?>">
	<input type="hidden" name="dateFrom" value="<?= $fetch['dateFrom'] ?>">
	<input type="hidden" name="dateTo" value="<?= $fetch['dateTo'] ?>">
	<input type="hidden" name="server" value="<?= $fetch['server'] ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<center><img class="align-self-center" src="<?php echo base_url('assets/img/logo/agc_logo.jpg'); ?>" width="60%"></center>
			</div>
			<div class="col-md-6" style="text-align:center;">
				<h4>Alturas Supermarket Corporation</h4>
				<p style="margin-top:0px; margin-bottom: 1px;">Dampas District, Tagbilaran City, Philippines</p>
				<p style="margin-top:0px; margin-bottom: 1px;">Tel. No: (038) 501-3000; Local: 1313; Fax No: 501-9245</p>
				<p style="margin-top:0px; margin-bottom: 1px;">Email Add: corporatehrd@alturasbohol.com</p>
			</div>
			<div class="col"></div>
		</div>
		<div style="padding-top: 80px;">
			<h5>
				<?php
				if (!empty($violation['agency'])) {

					echo $violation['agency'];
				} else {

					echo $violation['company'];
				}
				?>
			</h5>
		</div>
		<div style="padding-top: 35px;">
			<p>Dear Sir/Ma'am:</p>
			<p style="margin-top:30px;">This is in reference to you merchandiser, <b>MR. <?= strtoupper($violation['fullname']) ?></b> presently assigned in our establishment handling <b><?= $violation['company'] ?></b> wherein he/she violated our company House Rules and Regulations with suspension/s as a corresponding disciplinary action.</p>
		</div>
		<div style="margin-top:30px;">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>
							<center>Violation's Commited</center>
						</th>
						<th>
							<center>Area of Assignment</center>
						</th>
						<th>
							<center>Violation Details</center>
						</th>
						<th>
							<center>Number of Suspension/s</center>
						</th>
					</tr>
				</thead>
				<tbody style="font-size:12px;">
					<?php

					foreach ($violation['violation_detail'] as $key) { ?>

						<tr>
							<td style="text-align: center; vertical-align: middle;"><b><?= $key['violation'] ?></b></td>
							<td style="text-align: center; vertical-align: middle;"><b><?= $key['store'] . '- ' . $key['department'] ?></b></td>
							<td>
								<?php if (count($key['violation_prev_details']) > 0) { ?>
									<b>Previous :</b>
									<ol>
										<?php
										foreach ($key['violation_prev_details'] as $prev_detail) { ?>
											<li style="list-style-type: none;"><?= $prev_detail->detail ?></li> <?php
																											}
																												?>
									</ol>
									<hr>
								<?php } ?>

								<b>Present :</b>
								<ol>
									<?php

									foreach ($key['violation_details'] as $detail) { ?>
										<li style="list-style-type: none;"><?= $detail->detail ?></li> <?php
																									}
																										?>
								</ol>
							</td>
							<td style="text-align: center; vertical-align: middle;"><b><?= $key['suspension'] ?> day</b></td>
						</tr><?php
							}
								?>
				</tbody>
			</table>
		</div>
		<div style="padding-top: 20px;">
			<p>The above infraction/s forms part of his/her negative performance and attitude at work, hence we are requesting for an immidiate action regarding this matter.</p>
			<p style="margin-top:30px;">Thank you and more power.</p>
			<p style="margin-top:30px;">Respectfully yours,</p>
			<p style="margin-top:30px;"><b>MARIA NORA PAHANG</b></p>
			<p>HRD Manager</p>
		</div>
	</div>
<?php
} else if ($request == "view_penalty") {
?>
	<style>
		.dt_penalty th,
		.dt_penalty td {
			font-size: 14px;
		}

		#table-penalty th,
		#table-penalty td {
			font-size: 11px;
		}
	</style>
	<div class="row">
		<div class="col-12">
			<div class="text-center">
				<h5>Fresh Market Division</h5>
				<h5>Concessionaire Penalty for Insufficient and No Personnel</h5>
			</div>
		</div>
		<div class="mt-4 pl-3 pr-3">
			<div class="row">
				<div class="col-md-12">
					<table class="dt_penalty" width="100%">
						<tr>
							<td><label>SUPPLIER :</label></td>
							<td>
								<label><?= $vendor->vendor_name ?></label>
							</td>
						</tr>
						<tr>
							<td><label>STORE :</label></td>
							<td>
								<label><?= $store->business_unit ?></label>
							</td>
						</tr>
						<tr>
							<td><label>CUT-OFF :</label></td>
							<td>
								<label><?= date('F d-', strtotime($penalty->date_from)) . '' . date('d, Y', strtotime($penalty->date_to)) ?></label>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="mt-3 pl-3 pr-3">
			<div class="row">
				<div class="col-md-12">
					<table class="dt_penalty" width="100%">
						<tr>
							<td width="40%">
								<label>MINIMUM HALF MONTH SALES REQUIREMENT:</label>
							</td>
							<td>
								<label>₱ <?= $penalty->min_half_month_sales ?></label>
							</td>
						</tr>
						<tr>
							<td>
								<label>MINIMUM DAILY SALES REQUIREMENT:</label>
							</td>
							<td>
								<label>₱ <?= $penalty->min_daily_sales ?></label>
							</td>
						</tr>
						<tr>
							<td>
								<label>PENALTY FOR NOT HITTING THE 80% REQUIRED PERSONNEL:</label>
							</td>
							<td>
								<label>₱ <?= $penalty->penalty_insu_personnel ?></label>
							</td>
						</tr>
						<tr>
							<td>
								<label>REQUIRED STATIONED PROMODISERS:</label>
							</td>
							<td>
								<label><?= $penalty->required_personnel ?></label>
							</td>
						</tr>
						<tr>
							<td>
								<label>80% OF REQUIRED STATIONED PROMODISERS:</label>
							</td>
							<td>
								<label><?= round($penalty->required_personnel * .8) ?></label>
							</td>
							</ tr>
						<tr>
							<td>
								<label>50% OF REQUIRED STATIONED PROMODISERS:</label>
							</td>
							<td>
								<label><?= round($penalty->required_personnel * .5) ?></label>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-1">
					<p class="text-muted">NOTED:</p>
				</div>
				<div class="col-md-10">
					<p class="text-muted">
						HALF OF THE REQUIRED STATIONED PROMODISERS IS CONSIDERED
						<br />
						NO PERSONNEL AND SUBJECT FOR PENALTY
					</p>
					<p class="text-muted">
						THE STORE WILL EITHER RECORD THE P.O.S. TRANSACTED DAILY
						SALES OR THE AGREED DAILY MINIMUM <br />
						SALES REQUIREMENT, WHICHEVER IS HIGHER, AS THE DAILY SALES
						FOR EACH DAY W/OUT ANY CONCESSIONAIRE <br />
						SALES PERSONNEL
					</p>
				</div>
			</div>

			<div class="table-responsive mt-3">
				<table id="table-penalty" class="table table-bordered table-sm" width="100%">
					<thead>
						<tr>
							<th>DATE</th>
							<th style="text-align: center; vertical-align: middle;">
								# OF PERSONNEL ON DUTY
							</th>
							<th>REMARKS</th>
							<th style="text-align: center; vertical-align: middle;">
								POS TRANSACTED DAILY SALES
							</th>
							<th style="text-align: center; vertical-align: middle;">
								AGREED DAILY MINIMUM SALES REQUIREMENT
							</th>
							<th style="text-align: center; vertical-align: middle;">
								20%
							</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$total_penalty = 0;
						foreach ($penalty_perday as $p) { ?>

							<tr>
								<th width="5%"><?= date('m/d/y', strtotime($p->date)) ?></th>
								<th style="text-align: center; vertical-align: middle;">
									<?= $p->no_personnel * 1 ?>
								</th>
								<th><?= $p->remarks ?></th>
								<th style="text-align: center; vertical-align: middle;">
									<?php
									if ($p->pos_daily_sales != '') {
										echo '₱ ' . number_format($p->pos_daily_sales, 2);
									}
									?>
								</th>
								<th style="text-align: center; vertical-align: middle;" width="23%">
									<?php
									if ($p->min_sales_req != '') {
										echo '₱ ' . number_format($p->min_sales_req, 2);
									}
									?>
								</th>
								<th style="text-align: center; vertical-align: middle;" width="20%">
									<?php
									if ($p->penalty != '') {
										echo '₱ ' . number_format($p->penalty, 2);
									}
									?>
								</th>
							</tr> <?php

									$total_penalty += $p->penalty;
								}
									?>

						<tr>
							<th>TOTAL</th>
							<th style="text-align: center; vertical-align: middle;">
								<?= $penalty->total_personnel ?>
							</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
						<tr>
							<th>AVERAGE</th>
							<th style="text-align: center; vertical-align: middle;">
								<?= round($penalty->average_personnel) ?>
							</th>
							<th></th>
							<th></th>
							<th></th>
							<th style="text-align: center; vertical-align: middle;">
								<?php
								if ($penalty->average_personnel < round($penalty->required_personnel * .8)) {
									echo '₱ ' . number_format($penalty->penalty_insu_personnel, 2);
									$total_penalty += $penalty->penalty_insu_personnel;
								}
								?>
							</th>
						</tr>
						<tr>
							<th colspan="5" style="text-align: right; vertical-align: middle;">
								<span>TOTAL AMOUNT FOR PENALTY</span>
							</th>
							<th style="text-align: center; vertical-align: middle;">
								₱ <?= number_format($total_penalty, 2); ?>
							</th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
} else if ($request == "chat_messages") {

	$supplier = $this->login_model->supplier_details($_SESSION['userId']);

	foreach ($messages as $msg) {

		if ($msg->userId == $_SESSION['supplier_id']) { ?>

			<div class="sudlanan darker">
				<img src="<?= base_url() . '' . $supplier->photo ?>" alt="Avatar" class="right" style="width: 36px; height: 36px;">
				<p><?= $msg->message ?></p>
				<span class="time-left">11:01</span>
			</div>
		<?php

		} else { ?>

			<div class="sudlanan">
				<img src="<?= base_url('assets/img/user/user-1.png') ?>" alt="Avatar" style="width: 36px; height: 36px;">
				<p><?= $msg->message ?></p>
				<span class="time-right">11:00</span>
			</div>
<?php
		}
	}
}
?>