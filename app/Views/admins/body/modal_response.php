<?php

if ($request == "view_message") {

	$messages 	= $this->email_model->view_message($start);
	$today 		= date('Y-m-d');
	$yesterday 	= date('Y-m-d', strtotime("-1 days"));

	foreach ($messages as $msg) {

		$sender_photo = $this->email_model->view_picture($msg->userId)['photo'];

		if (date('Y-m-d', strtotime($msg->date_submitted)) == $today) {

			$date_sent = "Today";
		} else if (date('Y-m-d', strtotime($msg->date_submitted)) == $yesterday) {

			$date_sent = "Yesterday";
		} else {

			$date_sent = date('M. d, Y', strtotime($msg->date_submitted));
		}

		if ($msg->read == 0) { ?>

			<li class="list-group-item unread">
				<div class="email-checkbox">
					<label>
						<i class="far fa-square"></i>
						<input type="checkbox" data-checked="email-checkbox" name="chkId[]" value="<?php echo $msg->msg_id; ?>" />
					</label>
				</div>
				<a href="javascript:;" onclick="view_details(<?php echo $msg->msg_id; ?>)" class="email-user">
					<img src="<?php echo 'http://172.16.43.134/altsportal/' . $sender_photo; ?>" loading="lazy" alt="" />
				</a>
				<div class="email-info">
					<a href="javascript:;" onclick="view_details(<?php echo $msg->msg_id; ?>)">
						<span class="email-time"><?php echo $date_sent; ?></span>
						<span class="email-sender"><?php echo $msg->sender; ?></span>
						<span class="email-title"><?php echo $msg->subject; ?></span>
						<span class="email-desc"><?php echo $msg->message; ?></span>
					</a>
				</div>
			</li> <?php
				} else { ?>

			<li class="list-group-item">
				<div class="email-checkbox">
					<label>
						<i class="far fa-square"></i>
						<input type="checkbox" data-checked="email-checkbox" name="chkId[]" value="<?php echo $msg->msg_id; ?>" />
					</label>
				</div>
				<a href="javascript:;" onclick="view_details(<?php echo $msg->msg_id; ?>)" class="email-user">
					<img src="<?php echo 'http://172.16.43.134/altsportal/' . $sender_photo; ?>" alt="" />
				</a>
				<div class="email-info">
					<a href="javascript:;" onclick="view_details(<?php echo $msg->msg_id; ?>)">
						<span class="email-time"><?php echo $date_sent; ?></span>
						<span class="email-sender"><?php echo $msg->sender; ?></span>
						<span class="email-title"><?php echo $msg->subject; ?></span>
						<span class="email-desc"><?php echo $msg->message; ?></span>
					</a>
				</div>
			</li>
	<?php
				}
			}
		} else if ($request == "view_details") {

			$read 	= $this->email_model->update_message($msgId);
			$row 	= $this->email_model->message_details($msgId);
			$photo  = $this->email_model->view_picture($row->userId)['photo'];

			$today 		= date('Y-m-d');
			$yesterday 	= date('Y-m-d', strtotime("-1 days"));

			if (date('Y-m-d', strtotime($row->date_submitted)) == $today) {

				$date_sent = "Today";
				$time_sent = date('h:i A', strtotime($row->date_submitted));
			} else if (date('Y-m-d', strtotime($row->date_submitted)) == $yesterday) {

				$date_sent = "Yesterday";
				$time_sent = date('h:i A', strtotime($row->date_submitted));
			} else {

				$date_sent = date('M. d, Y', strtotime($row->date_submitted));
				$time_sent = date('h:i A', strtotime($row->date_submitted));
			}

			$row_num = "SELECT * FROM (
					SELECT T.*,(@rownum := @rownum + 1) AS rownum FROM (
					SELECT msg_id FROM message ORDER BY date_submitted DESC ) AS T
					JOIN    (SELECT @rownum := 0) r 
					) AS w where msg_id = $msgId";
			$row_position = $this->email_model->return_row_array($row_num)['rownum'];
	?>
	<input type="hidden" name="row_position_handler" value="<?php echo $row_position; ?>">
	<h3 class="m-t-0 m-b-15 f-w-500"><?php echo $row->subject; ?></h3>
	<ul class="media-list underline m-b-15 p-b-15">
		<li class="media media-sm clearfix">
			<a href="javascript:;" class="pull-left">
				<img class="media-object rounded-corner" alt="" src="<?php echo 'http://altsportal.com/' . $photo; ?>" />
			</a>
			<div class="media-body">
				<div class="email-from text-inverse f-s-14 f-w-600 m-b-3">
					From: <?php

							if (trim($row->email) != "") {

								echo "$row->sender ($row->email)";
							} else {

								echo $row->sender;
							}
							?>
				</div>
				<div class="m-b-3"><i class="fa fa-clock fa-fw"></i> <?php echo $date_sent . ', ' . $time_sent; ?></div>
			</div>
		</li>
	</ul>

	<p class="f-s-12 text-inverse p-t-10">
		<?php echo nl2br($row->message); ?>
	</p>
	<script type="text/javascript">
		$(document).ready(function() {

			var row_position = $("input[name = 'row_position_handler']").val();
			var total_msg = $("input[name = 'total_msg']").val();
			var previous = $("input[name = 'previous_page']").val();
			var next = $("input[name = 'next_page']").val();

			row_position = (row_position * 1);
			previous = row_position - 1;
			next = row_position + 1;

			$("input.row_position").val(row_position);

			if (row_position >= 0) {

				if (row_position <= total_msg) {

					$("input[name = 'previous_page']").val(previous);
					$("input[name = 'next_page']").val(next);
				}
			} else {

				$("input[name = 'previous_page']").val(previous);
				$("input[name = 'next_page']").val(next);
			}
		});
	</script>
<?php
		} else if ($request == "view_message_detail") {

			$row 	= $this->email_model->view_message_detail($start);
			$read 	= $this->email_model->update_message($row->msg_id);
			$photo  = $this->email_model->view_picture($row->userId)['photo'];

			$today 		= date('Y-m-d');
			$yesterday 	= date('Y-m-d', strtotime("-1 days"));

			if (date('Y-m-d', strtotime($row->date_submitted)) == $today) {

				$date_sent = "Today";
				$time_sent = date('h:i A', strtotime($row->date_submitted));
			} else if (date('Y-m-d', strtotime($row->date_submitted)) == $yesterday) {

				$date_sent = "Yesterday";
				$time_sent = date('h:i A', strtotime($row->date_submitted));
			} else {

				$date_sent = date('M. d, Y', strtotime($row->date_submitted));
				$time_sent = date('h:i A', strtotime($row->date_submitted));
			}
?>

	<h3 class="m-t-0 m-b-15 f-w-500"><?php echo $row->subject; ?></h3>
	<ul class="media-list underline m-b-15 p-b-15">
		<li class="media media-sm clearfix">
			<a href="javascript:;" class="pull-left">
				<img class="media-object rounded-corner" alt="" src="<?php echo 'http://altsportal.com/' . $photo; ?>" />
			</a>
			<div class="media-body">
				<div class="email-from text-inverse f-s-14 f-w-600 m-b-3">
					From: <?php

							if (trim($row->email) != "") {

								echo "$row->sender ($row->email)";
							} else {

								echo $row->sender;
							}
							?>
				</div>
				<div class="m-b-3"><i class="fa fa-clock fa-fw"></i> <?php echo $date_sent . ', ' . $time_sent; ?></div>
			</div>
		</li>
	</ul>

	<p class="f-s-12 text-inverse p-t-10">
		<?php echo nl2br($row->message); ?>
	</p><?php
		} else if ($request == "dataTable") { ?>

	<link href="<?php echo base_url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/DataTables/extensions/Scroller/css/scroller.bootstrap.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css'); ?>" rel="stylesheet" />
<?php
		} else if ($request == "extend_contract") {

			$row = $this->setup_model->promo_company_user_details($userId);

			if ($row->agency_code != 0) {

				$supplier = $this->setup_model->get_supplier('agency_name', 'promo_locate_agency', 'agency_code', $row->agency_code)['agency_name'];
			} else {

				$supplier = $this->setup_model->get_supplier('company_name', 'promo_locate_company', 'company_code', $row->company_code)['company_name'];
			}

?>
	<input type="hidden" name="userId" value="<?php echo $userId; ?>">
	<input type="hidden" name="prev_dateFrom" value="<?php echo $row->dateFrom; ?>">
	<input type="hidden" name="prev_dateTo" value="<?php echo $row->dateTo; ?>">
	<div class="alert alert-info">
		<h5><i class="fa fa-info-circle"></i> Contract History</h5>
		<p><?php echo "$supplier current contract is from " . date('F d, Y', strtotime($row->dateFrom)) . " to " . date('F d, Y', strtotime($row->dateTo)) . "."; ?></p>
	</div>
	<div class="form-group">
		<label>Date From</label>
		<input type="text" id="fromDate" name="fromDate" class="form-control" placeholder="click to select the date" onchange="inputField(this.name)" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
	</div>
	<div class="form-group">
		<label>Date To</label>
		<input type="text" id="toDate" name="toDate" class="form-control" placeholder="click to select the date" onchange="dp_todate(this.name)" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {

			var dateFrom = "<?php echo date('Y-m-d', strtotime($row->dateTo)); ?>";
			var dateToday = new Date(dateFrom);

			$("#fromDate").datepicker({

				changeYear: true,
				changeMonth: true,
				minDate: dateToday,
				onSelect: function(selectedDate) {

					$("#toDate").datepicker('option', 'minDate', selectedDate);
					$("#fromDate").removeClass("parsley-error").addClass("parsley-success");
				}
			});

			$("#toDate").datepicker({

				changeYear: true,
				changeMonth: true,
			});

			$("[data-mask]").inputmask();
		});
	</script>
<?php
		} else if ($request == "setup_cutoff") {

?>
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/switcher/css/switcher.css'); ?>">

	<input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">
	<input type="hidden" name="trigger">
	<input type="hidden" name="triggerAll">
	<table class="table table-bordered" width="100%">
		<tr>
			<th width="50%">Cut-off</th>
			<td width="50%" class="text-center"><input type="checkbox" class="chkA chk_0" onchange="chk(0)" /></td>
		</tr>
		<?php

			$cutoffs = $this->setup_model->list_of_cutoffs();
			$i = 1;
			foreach ($cutoffs as $row) {

				if ($row['endFC'] == '') {

					$endFC = "Last";
				} else {

					$endFC = $row['endFC'];
				}

				$statCut = $row['statCut'];
				$promo_cutOff = $row['startFC'] . "-" . $endFC . " / " . $row['startSC'] . "-" . $row['endSC'];

				$co_num = $this->setup_model->chk_cutoff($supplier_id, $statCut); ?>

			<tr>
				<th><?php echo $promo_cutOff; ?></th>
				<td class="text-center">
					<input type="checkbox" name="cutoff[]" value="<?php echo $statCut; ?>" class="chkC chk_<?php echo $i; ?>" <?php if ($co_num > 0) : echo "checked=''";
																																endif; ?> onchange="chk(<?php echo $i; ?>)">
				</td>
			</tr><?php
					$i++;
				}
					?>
	</table>
	<script src="<?php echo base_url('assets/plugins/switcher/js/jquery.switcher.min.js'); ?>"></script>
	<script type="text/javascript">
		$(function() {

			$.switcher();

			$("input[name = 'trigger']").val('');
			$("input[name = 'triggerAll']").val('');
			var chk = $("input[name = 'cutoff[]']");
			var newCHK = "";
			var count = 0;

			for (var i = 0; i < chk.length; i++) {

				if (chk[i].checked == true) {

					count++;
				}
			}

			if (i == count) {

				$("input[name = 'trigger']").val("true");
				$("input.chk_0").trigger("click"); // turn it on
			}
		});
	</script><?php
			} else if ($request == "create_account") { ?>

	<div class="row">
		<div class="col-md-6">
			<div class="input-group m-b-10">
				<div class="input-group-prepend">
					<span class="input-group-text"><input type="radio" name="account" id="with_agency" class="with_agency" checked=""></span>
				</div>
				<input type="text" class="form-control with_agency" placeholder="With Agency">
			</div>
		</div>
		<div class="col-md-6">
			<div class="input-group m-b-10">
				<div class="input-group-prepend">
					<span class="input-group-text"><input type="radio" name="account" id="no_agency" class="no_agency"></span>
				</div>
				<input type="text" class="form-control no_agency" placeholder="No Agency">
			</div>
		</div>
	</div>
	<br>
	<div class="col-md-12">
		<input type="hidden" name="account_form" value="with_agency">
		<div class="form-group">
			<a href="javascript:;" class="btn btn-primary userpass">Generate Username</a>
		</div>
		<div class="form-group">
			<label>Username:</label>
			<input type="text" name="username" class="form-control">
		</div>
		<div class="form-group">
			<label>Password:</label>
			<input type="text" name="password" class="form-control" readonly="" value="Altsportal2019">
		</div>
		<div class="create_account_form">

		</div>
		<div class="form-group">
			<label>Tag to Group 3</label>
			<select name="group3" class="form-control">
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Date From:</label>
					<input type="text" id="dateFrom" name="dateFrom" class="form-control" placeholder="click to select the date" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Date To:</label>
					<input type="text" id="dateTo" name="dateTo" class="form-control" placeholder="click to select the date" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {

			$.ajax({
				type: "POST",
				url: "<?php echo site_url('create_account_form'); ?>",
				data: {
					account_form: "with_agency"
				},
				success: function(result) {

					$("div.create_account_form").html(result);
				}
			});

			$("input.no_agency").click(function() {

				$("input#no_agency").prop("checked", true);
				$("input#no_agency").focus();

				$.ajax({
					type: "POST",
					url: "<?php echo site_url('create_account_form'); ?>",
					data: {
						account_form: "no_agency"
					},
					success: function(result) {

						$("div.create_account_form").html(result);
					}
				});
			});

			$("input.with_agency").click(function() {

				$("input#with_agency").prop("checked", true);
				$("input#with_agency").focus();

				$.ajax({
					type: "POST",
					url: "<?php echo site_url('create_account_form'); ?>",
					data: {
						account_form: "with_agency"
					},
					success: function(result) {

						$("div.create_account_form").html(result);
					}
				});
			});

			var dateToday = new Date();
			$("#dateFrom").datepicker({

				changeYear: true,
				changeMonth: true,
				minDate: dateToday,
				onSelect: function(selectedDate) {

					$("#dateTo").datepicker('option', 'minDate', selectedDate);
					$("#dateFrom").removeClass("parsley-error").addClass("parsley-success");
				}
			});

			$("#dateTo").datepicker({

				changeYear: true,
				changeMonth: true,
			});

			$("[data-mask]").inputmask();

			$("input[name = 'dateFrom']").change(function() {

				$(this).removeClass("parsley-error").addClass("parsley-success");
			});

			$("input[name = 'dateTo']").change(function() {

				var dateFrom = $("input[name = 'dateFrom']").val();

				if (dateFrom == "") {

					$(this).val('');
					Swal(
						'Warning',
						'Please select From Date field first!',
						'warning'
					);
				} else {

					$(this).removeClass("parsley-error").addClass("parsley-success");
				}
			});

			$("a.userpass").click(function() {

				$("input[name = 'username']").removeClass("parsley-error").addClass("parsley-success");
				// $("input[name = 'password']").removeClass("parsley-error").addClass("parsley-success");

				var username = random_username(6);
				// var password = random_password(12);

				$("input[name = 'username']").val(username);
				// $("input[name = 'password']").val(password);
			});

		});
	</script><?php
			} else if ($request == "create_account_form") {

				if ($account_form == "with_agency") {

					$agencies = $this->setup_model->list_of_agency();

				?>

		<input type="hidden" name="company" value="0">
		<div class="form-group">
			<label>Select Agency:</label>
			<select class="form-control" name="agency">
				<option value=""> Select Agency </option>
				<?php

					foreach ($agencies as $agency) {

						echo '<option value="' . $agency->agency_code . '">' . $agency->agency_name . '</option>';
					}
				?>
			</select>
		</div>
		<script type="text/javascript">
			$(function() {

				$("select[name = 'agency']").change(function() {

					var agency = $(this).val();

					if (agency.trim() != "") {

						$(this).removeClass("parsley-error").addClass("parsley-success");
					}
				});
			});
		</script><?php
				} else {

					$companies = $this->setup_model->list_of_company();

					?>
		<input type="hidden" name="agency" value="0">
		<div class="form-group">
			<label>Company:</label>
			<select name="company" class="form-control" onchange="inputField(this.name)">
				<option value=""> Select Company </option>
				<?php

					foreach ($companies as $company) {

						echo '<option value="' . $company->pc_name . '">' . $company->pc_name . '</option>';
					}
				?>
			</select>
		</div><?php
				}
			} else if ($request == "setupan_agency") {

				?>
	<div class="table-responsive">
		<table id="dt_companies" class="table table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<th>Company Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$agency_code = $this->input->post('setupan_agency', TRUE);
				$fetch_data = $this->setup_model->list_of_company();

				$data = array();
				$x = 1;
				foreach ($fetch_data as $row) {

					$chkCom = $this->setup_model->chk_locate_company($agency_code, $row->pc_name);
					if ($chkCom > 0) {

						$action =  '<input type="checkbox" name="" class="chk_' . $x . '" value="' . $row->pc_name . '" checked="" onclick="chked_(' . $x . ')">';
						echo '<input type="checkbox" name="chkCom[]" class="chkA_' . $x . '" value="' . $row->pc_name . '" checked="" style="display:none;">';
					} else {

						$action =  '<input type="checkbox" name="" class="chk_' . $x . '" value="' . $row->pc_name . '" onclick="chked_(' . $x . ')">';
						echo '<input type="checkbox" name="chkCom[]" class="chkA_' . $x . '" value="' . $row->pc_name . '" style="display:none;">';
					}

					echo '
									<tr>
										<td>' . $row->pc_name . '</td>
										<td>' . $action . '</td>
									</tr>
								';

					$x++;
				}
				?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		$("table#dt_companies").DataTable({

			"destroy": true,
			"paging": false
		});
	</script><?php
			} else if ($request == "add_adminUser") {

				?>
	<div class="form-group">
		<label>Fullname:</label>
		<input type="text" name="fullname" class="form-control" placeholder="Lastname, Firstname Middlename" autocomplete="false" onkeyup="inputField(this.name)">
	</div>
	<div class="row">
		<div class="col-md-6">
			<label>Username:</label>
			<input type="text" name="username" class="form-control" autocomplete="false" onkeyup="inputField(this.name)">
		</div>
		<div class="col-md-6">
			<label>Server Location:</label>
			<select name="server_loc" class="form-control" onchange="inputField(this.name)">
				<option value="">Select Server Location</option>
				<option value="server_tag">Corporate</option>
				<option value="server_tal">Alturas Talibon</option>
				<option value="server_tub">Alturas Tubigon</option>
				<option value="server_colonnade">Colonnade</option>
			</select>
		</div>
	</div><?php
			} else if ($request == "update_adminUser") {

				$row = $this->setup_model->adminUser($user_no);

			?>
	<input type="hidden" name="user_no" value="<?php echo $user_no; ?>">
	<div class="form-group">
		<label>Fullname:</label>
		<input type="text" name="fullname" class="form-control" placeholder="Lastname, Firstname Middlename" autocomplete="false" value="<?php echo $row->fullname; ?>" onkeyup="inputField(this.name)">
	</div>
	<div class="row">
		<div class="col-md-6">
			<label>Username:</label>
			<input type="text" name="username" class="form-control" autocomplete="false" value="<?php echo $row->username; ?>" onkeyup="inputField(this.name)">
		</div>
		<div class="col-md-6">
			<label>Server Location:</label>
			<select name="server_loc" class="form-control" onchange="inputField(this.name)">
				<option value="">Select Server Location</option>
				<option value="server_tag" <?php if ($row->server == "server_tag") : echo "selected=''";
											endif; ?>>Corporate</option>
				<option value="server_tal" <?php if ($row->server == "server_tal") : echo "selected=''";
											endif; ?>>Alturas Talibon</option>
				<option value="server_tub" <?php if ($row->server == "server_tub") : echo "selected=''";
											endif; ?>>Alturas Tubigon</option>
				<option value="server_colonnade" <?php if ($row->server == "server_colonnade") : echo "selected=''";
													endif; ?>>Colonnade</option>
			</select>
		</div>
	</div><?php
			} else if ($request == "update_agency") {

				$agency_name = $this->setup_model->fetch_agency_name($agency_code)['agency_name'];

			?>
	<input type="hidden" name="agency_code" value="<?php echo $agency_code; ?>">
	<div class="form-group">
		<label>Agency Name:</label>
		<input type="text" name="agencyName" class="form-control" value="<?php echo $agency_name; ?>">
	</div><?php
			} else if ($request == "update_company") {

				$company_name = $this->setup_model->fetch_company_name($pc_code)['pc_name'];

			?>
	<input type="hidden" name="pc_code" value="<?php echo $pc_code; ?>">
	<div class="form-group">
		<label>Company Name:</label>
		<input type="text" name="companyName" class="form-control" value="<?php echo $company_name; ?>">
	</div><?php
			} else if ($request == "users_xls") {

				$filename = "Users Report";
				header("Content-Type: application/vnd.ms-excel");
				header("Content-disposition: attachment; filename=" . $filename . ".xls");

			?>

	<center>
		<h3>Users Report Report as of <?php echo date("F d, Y"); ?></h3>
	</center>

	<table class='table table-bordered' border='1'>
		<tr>
			<th>No</th>
			<th>Agency/Company</th>
			<th>Username</th>
			<th>Password</th>
			<th>Status</th>
			<th>Date Created</th>
			<th>Login</th>
			<th>Last Login</th>
		</tr>
		<?php

				$num = 1;
				$fetch_data = $this->setup_model->users_account();
				$data = array();
				foreach ($fetch_data as $row) {

					$last_login = "";
					if ($row->last_login != "") {

						$last_login = date('m/d/Y', strtotime($row->last_login));
					}

					$first_login = "";
					if ($row->first_login != "") {

						$first_login = date('m/d/Y', strtotime($row->first_login));
					}

					if ($row->status == 1) {
						$status = 'Active';
					} else {
						$status = 'Inactive';
					}

					echo "
							<tr>
								<td>" . $num . "</td>
								<td>" . $row->supplier . "</td>
								<td>" . $row->username . "</td>
								<td>Altsportal2019</td>
								<td>" . $status . "</td>
								<td>" . date('m/d/Y', strtotime($row->created_at)) . "</td>
								<td>" . $first_login . "</td>
								<td>" . $last_login . "</td>
							</tr>
						";
					$num++;
				}
		?>
	</table>
<?php
			} else if ($request == "add_supplier_details") {
?>
	<input type="hidden" name="action" value="create">
	<div class="form-group">
		<label>Supplier</label>
		<input type="text" name="supplier" class="form-control" required="">
	</div>
	<div class="form-group">
		<label>Supplier Type</label>
		<select name="supplier_type" class="form-control" required="">
			<option value=""> --Select -- </option>
			<?php
				foreach ($supplier_types as $type) {

					echo "<option value='" . $type->id . "'>" . $type->type . "</option>";
				}
			?>
		</select>
	</div>
<?php
			} else if ($request == "edit_supplier_details") {
?>
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="id" value="<?= $supplier->id ?>">
	<div class="form-group">
		<label>Supplier</label>
		<input type="text" name="supplier" class="form-control" value="<?= $supplier->supplier; ?>" required="">
	</div>
	<div class="form-group">
		<label>Supplier Type</label>
		<select name="supplier_type" class="form-control" required="">
			<option value=""> --Select -- </option>
			<?php
				foreach ($supplier_types as $type) { ?>

				<option value="<?= $type->id ?>" <?php if ($type->id == $supplier->supplier_type) {
														echo "selected";
													} ?>><?= $type->type ?></option><?php
																																												}
																																													?>
		</select>
	</div>
<?php
			} else if ($request == "add_supplier_account") {
?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Supplier:</label>
				<select name="supplier" class="form-control" required="" onchange="setup_supplier(this.value)">
					<option value=""> -- Select -- </option>
					<?php
					foreach ($supplier as $s) {
						echo "<option value='" . $s->id . "'>" . $s->supplier . "</option>";
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-6"></div>
	</div>
	<div class="loading" style="display: none;"><i class="fas fa-spinner fa-pulse"></i> Plese wait...</div>
	<br>
	<div class="setup_supplier"></div>
<?php
			} else if ($request == "setup_supplier") {
?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<a href="javascript:;" class="btn btn-primary userpass">Generate Username</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Username:</label>
				<input type="text" class="form-control" name="username" required="">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Password:</label>
				<input type="text" name="password" class="form-control" value="<?= 'Altsportal2019' ?>" readonly="" required>
			</div>
		</div>
	</div>
	<input type="hidden" name="supplier_type" value="<?= $supplier->supplier_type ?>">
	<?php
				if ($supplier->supplier_type == 1) {
	?>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Select Agency <small>(Bohol Server)</small></label>
					<input type="hidden" name="alturas_company" value="0">
					<select name="alturas_agency" class="form-control">
						<option value=""> -- Select --</option>
						<?php
						foreach ($alturas_agency as $aa) {
							echo "<option value='" . $aa->agency_code . "'>" . $aa->agency_name . "</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Select Agency <small>(Colonnade Server)</small></label>
					<input type="hidden" name="colonnade_company" value="0">
					<select name="colonnade_agency" class="form-control">
						<option value=""> -- Select --</option>
						<?php
						foreach ($colonnade_agency as $ca) {
							echo "<option value='" . $ca->agency_code . "'>" . $ca->agency_name . "</option>";
						}
						?>
					</select>
				</div>
			</div>
		</div>
	<?php
				} else {
	?>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Select Company <small>(Bohol Server)</small></label>
					<input type="hidden" name="alturas_agency" value="0">
					<select name="alturas_company" class="form-control">
						<option value=""> -- Select --</option>
						<?php
						foreach ($alturas_company as $ac) {
							echo "<option value='" . $ac->pc_code . "'>" . $ac->pc_name . "</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Select Company <small>(Colonnade Server)</small></label>
					<input type="hidden" name="colonnade_agency" value="0">
					<select name="colonnade_company" class="form-control">
						<option value=""> -- Select --</option>
						<?php
						foreach ($colonnade_company as $cc) {
							echo "<option value='" . $cc->pc_code . "'>" . $cc->pc_name . "</option>";
						}
						?>
					</select>
				</div>
			</div>
		</div>
	<?php
				}
	?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Date From:</label>
				<input type="text" id="dateFrom" name="dateFrom" class="form-control" placeholder="click to select the date" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask required="">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Date To:</label>
				<input type="text" id="dateTo" name="dateTo" class="form-control" placeholder="click to select the date" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask required="">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Email:</label>
				<input type="text" name="email" class="form-control" required>

			</div>
		</div>
	</div>
	<script type="text/javascript">
		var dateToday = new Date();
		$("#dateFrom").datepicker({

			changeYear: true,
			changeMonth: true,
			minDate: dateToday,
			onSelect: function(selectedDate) {

				$("#dateTo").datepicker('option', 'minDate', selectedDate);
			}
		});

		$("#dateTo").datepicker({

			changeYear: true,
			changeMonth: true,
		});

		$("[data-mask]").inputmask();
		$("input[name = 'dateTo']").change(function() {

			var dateFrom = $("input[name = 'dateFrom']").val();

			if (dateFrom == "") {

				$(this).val('');
				Swal(
					'Warning',
					'Please select From Date field first!',
					'warning'
				);
			}
		});

		$("a.userpass").click(function() {

			var username = random_username(6);
			// var password = random_password(12);

			$("input[name = 'username']").val(username);
		});
	</script>
<?php
			} else if ($request == 'setup_consignor') {

?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<a href="javascript:;" class="btn btn-primary userpass">Generate Username</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Username:</label>
				<input type="text" class="form-control" name="username" required="">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Password:</label>
				<input type="text" name="password" class="form-control" value="<?= 'Altsportal2019' ?>" readonly="" required>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Date From:</label>
				<input type="text" id="dateFrom" name="dateFrom" class="form-control" placeholder="click to select the date" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask required="">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Date To:</label>
				<input type="text" id="dateTo" name="dateTo" class="form-control" placeholder="click to select the date" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask required="">
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-12">
			<label>Select Vendor(s):</label>
			<div class="table-responsive">
				<table id="dt-vendors" class="table table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Vendor Code</th>
							<th>Vendor Name</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($vendor as $v) {

						?>
							<tr>
								<td><input type="checkbox" name="vendor_id[]" value="<?= $v->vendor_id ?>" style="width:20px; height:20px;"></td>
								<td><?= $v->vendor_code ?></td>
								<td><?= $v->vendor_name ?></td>
							</tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script>
		var dateToday = new Date();
		$("#dateFrom").datepicker({

			changeYear: true,
			changeMonth: true,
			minDate: dateToday,
			onSelect: function(selectedDate) {

				$("#dateTo").datepicker('option', 'minDate', selectedDate);
			}
		});

		$("#dateTo").datepicker({

			changeYear: true,
			changeMonth: true,
		});

		$("[data-mask]").inputmask();
		$("input[name = 'dateTo']").change(function() {

			var dateFrom = $("input[name = 'dateFrom']").val();

			if (dateFrom == "") {

				$(this).val('');
				Swal(
					'Warning',
					'Please select From Date field first!',
					'warning'
				);
			}
		});

		$("a.userpass").click(function() {

			var username = random_username(6);
			// var password = random_password(12);

			$("input[name = 'username']").val(username);
		});

		$(document).ready(function() {
			$('#dt-vendors').DataTable({
				"scrollY": "180px",
				"scrollCollapse": true,
				"paging": false
			});
		});
	</script>
<?php
			} else if ($request == "renew_contract") {
?>
	<input type="hidden" name="supplier_id" value="<?= $contract->supplier_id ?>">
	<input type="hidden" name="previous_datefrom" value="<?= $contract->date_from ?>">
	<input type="hidden" name="previous_dateto" value="<?= $contract->date_to ?>">
	<div class="alert alert-info">
		<h5><i class="fa fa-info-circle"></i> Contract History</h5>
		<p> <?= $contract->supplier ?> current contract is from <?= date('F d, Y', strtotime($contract->date_from)) ?> to <?= date('F d, Y', strtotime($contract->date_to)) ?></p>
	</div>
	<div class="form-group">
		<label>Date From</label>
		<input type="text" id="fromDate" name="fromDate" class="form-control" required="" placeholder="click to select the date" onchange="inputField(this.name)" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
	</div>
	<div class="form-group">
		<label>Date To</label>
		<input type="text" id="toDate" name="toDate" class="form-control" required="" placeholder="click to select the date" onchange="dp_todate(this.name)" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {

			var monthStart = "<?= date('Y-m'); ?>";
			var min = new Date(monthStart);

			var monthEnd = "<?= date('Y-m-t'); ?>";
			var max = new Date(monthEnd);

			$("#fromDate").datepicker({

				changeYear: true,
				changeMonth: true,
				minDate: min,
				maxDate: max,
				onSelect: function(selectedDate) {
					$("#toDate").datepicker('option', 'minDate', selectedDate);
					$("#fromDate").removeClass("parsley-error").addClass("parsley-success");
				}
			});

			$("#toDate").datepicker({
				changeYear: true,
				changeMonth: true,
				minDate: min,
			});

			$("[data-mask]").inputmask();
		});
	</script>
<?php
			} else if ($request == "edit_tag_ac") {
?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Supplier:</label>
				<input type="text" name="supplier" class="form-control" value="<?= $supplier->supplier ?>" readonly="">
			</div>
		</div>
		<div class="col-md-6"></div>
	</div>
	<input type="hidden" name="supplier_id" value="<?= $supplier->id ?>">
	<input type="hidden" name="user_id" value="<?= $user_id ?>">
	<input type="hidden" name="supplier_type" value="<?= $supplier->supplier_type ?>">
	<?php
				if ($supplier->supplier_type == 1) {
	?>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Select Agency <small>(Bohol Server)</small></label>
					<input type="hidden" name="alturas_company" value="0">
					<select name="alturas_agency" class="form-control">
						<option value=""> -- Select --</option>
						<?php
						foreach ($alturas_agency as $aa) { ?>
							<option value="<?= $aa->agency_code ?>" <?php if ($aa->agency_code == $alturas_code->agency_code) {
																		echo "selected";
																	} ?>><?= $aa->agency_name ?></option><?php
																																															}
																																																?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Select Agency <small>(Colonnade Server)</small></label>
					<input type="hidden" name="colonnade_company" value="0">
					<select name="colonnade_agency" class="form-control">
						<option value=""> -- Select --</option>
						<?php
						foreach ($colonnade_agency as $ca) { ?>
							<option value="<?= $ca->agency_code ?>" <?php if ($ca->agency_code == $colonnade_code->agency_code) {
																		echo "selected";
																	} ?>><?= $ca->agency_name ?></option><?php
																																															}
																																																?>
					</select>
				</div>
			</div>
		</div>
	<?php
				} else {
	?>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Select Company <small>(Bohol Server)</small></label>
					<input type="hidden" name="alturas_agency" value="0">
					<select name="alturas_company" class="form-control">
						<option value=""> -- Select --</option>
						<?php
						foreach ($alturas_company as $ac) { ?>
							<option value="<?= $ac->pc_code ?>" <?php if ($ac->pc_code == $alturas_code->company_code) {
																	echo "selected";
																} ?>><?= $ac->pc_name ?></option><?php
																																														}
																																															?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Select Company <small>(Colonnade Server)</small></label>
					<input type="hidden" name="colonnade_agency" value="0">
					<select name="colonnade_company" class="form-control">
						<option value=""> -- Select --</option>
						<?php
						foreach ($colonnade_company as $cc) { ?>
							<option value="<?= $cc->pc_code ?>" <?php if ($cc->pc_code == $colonnade_code->company_code) {
																	echo "selected";
																} ?>><?= $cc->pc_name ?></option><?php
																																														}
																																															?>
					</select>
				</div>
			</div>
		</div>
	<?php
				}
			} else if ($request == "employee_list") {

	?>
	<input type="hidden" name="cutoff" value="<?php echo $fetch['cutoff']; ?>">
	<input type="hidden" name="dateFrom" value="<?php echo $fetch['dateFrom']; ?>">
	<input type="hidden" name="dateTo" value="<?php echo $fetch['dateTo']; ?>">

	<?php if ($this->session->userdata('server') == 'server_tag') { ?>
		<input type="hidden" name="server" value="tagbilaran">
		<div id="wizard" class="sw-main sw-theme-default">
			<!-- begin wizard-step -->
			<ul class="nav nav-tabs step-anchor">
				<li class="col-md-4 col-sm-4 nav-item active" id="tagbilaran">
					<a href="javascript:void(0)" class="nav-link">
						<span class="number">1</span>
						<span class="info text-ellipsis">
							Tagbilaran, Bohol
							<small class="text-ellipsis">Alturas Mall, ICM , Plaza Marcela & Alta Citta</small>
						</span>
					</a>
				</li>
				<li class="col-md-4 col-sm-4 nav-item done" id="tubigon">
					<a href="javascript:void(0)" class="nav-link">
						<span class="number">2</span>
						<span class="info text-ellipsis">
							Tubigon, Bohol
							<small class="text-ellipsis">Alturas Tubigon</small>
						</span>
					</a>
				</li>
				<li class="col-md-4 col-sm-4 nav-item done" id="talibon">
					<a href="javascript:void(0)" class="nav-link">
						<span class="number">3</span>
						<span class="info text-ellipsis">
							Talibon, Bohol
							<small class="text-ellipsis">Alturas Talibon</small>
						</span>
					</a>
				</li>
			</ul>
			<!-- end wizard-step -->
			<!-- begin wizard-content -->
			<div class="sw-container tab-content" style="min-height: 366.181px;">
				<div class="table-responsive">
					<table id="dt_emp_list" class="table table-bordered table-hover" width="100%">
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
		</div><?php
				} else { ?>
		<input type="hidden" name="server" value="colonnade">
		<div class="table-responsive">
			<table id="dt_emp_list" class="table table-bordered table-hover" width="100%">
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
		</div> <?php
				} ?>
	<script type="text/javascript">
		var cutoff = $("input[name = 'cutoff']").val();
		var server = $("input[name = 'server']").val();
		var dateFrom = $("input[name = 'dateFrom']").val();
		var dateTo = $("input[name = 'dateTo']").val();

		employee_list(cutoff, server, dateFrom, dateTo);

		$("li.nav-item").click(function(e) {

			var id = $(this).attr('id');
			$(".nav-item").removeClass('active').addClass('done');
			$(this).removeClass('done').addClass('active');
			$("input[name = 'server']").val(id.trim());

			employee_list(cutoff, id, dateFrom, dateTo);
		})
	</script>
<?php
			} else if ($request == "company_list") {

?>
	<input type="hidden" name="cutoff" value="<?php echo $fetch['cutoff']; ?>">
	<input type="hidden" name="dateFrom" value="<?php echo $fetch['dateFrom']; ?>">
	<input type="hidden" name="dateTo" value="<?php echo $fetch['dateTo']; ?>">

	<?php

				$server = array('server_tag', 'server_tub', 'server_tal');

				if (in_array($this->session->userdata('server'), $server)) { ?>
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
		</div> <?php
				} else { ?>
		<input type="hidden" name="server" value="colonnade">
		<div class="table-responsive">
			<table id="dt_sup_violation_list" class="table table-bordered table-hover" width="100%">
				<thead>
					<tr>
						<th> Company</th>
						<th> Action</th>
					</tr>
				</thead>
			</table>
		</div> <?php
				} ?>
	<script type="text/javascript">
		var cutoff = $("input[name = 'cutoff']").val();
		var server = $("input[name = 'server']").val();
		var dateFrom = $("input[name = 'dateFrom']").val();
		var dateTo = $("input[name = 'dateTo']").val();

		company_list(cutoff, server, dateFrom, dateTo);

		$("li.nav-item").click(function(e) {

			var id = $(this).attr('id');
			$(".nav-item").removeClass('active').addClass('done');
			$(this).removeClass('done').addClass('active');
			$("input[name = 'server']").val(id.trim());

			company_list(cutoff, id, dateFrom, dateTo);
		})
	</script>
<?php
			} else if ($request == "upload_violation_form") {

?>
	<input type="hidden" name="cutoff" value="<?php echo $fetch['cutoff']; ?>">
	<input type="hidden" name="dateFrom" value="<?php echo $fetch['dateFrom']; ?>">
	<input type="hidden" name="dateTo" value="<?php echo $fetch['dateTo']; ?>">
	<input type="hidden" name="pc_code" value="<?php echo $fetch['pc_code']; ?>">
	<input type="hidden" name="server" value="<?php echo $fetch['server']; ?>">

	<div class="note note-yellow m-b-15">
		<div class="note-icon f-s-20">
			<i class="fa fa-lightbulb fa-2x"></i>
		</div>
		<div class="note-content">
			<h4 class="m-t-5 m-b-5 p-b-2">Demo Notes</h4>
			<ul class="m-b-5 p-l-25">
				<li>The maximum file size for uploads is <strong>2 MB</strong></li>
				<li>Only image files (<strong>JPG, PNG</strong>) are allowed</li>
			</ul>
		</div>
	</div>
	<?php if ($supplier['agency'] === 1) { ?>
		<div class="form-group">
			<label>Agency</label>
			<select name="agency" class="form-control" required="">
				<option value=""> --Select-- </option>
				<?php

					foreach ($supplier['agencies'] as $key) {

						$agency_code = $key->agency_code;
						$agency = $this->violation_model->agency_name($fetch['server'], $agency_code);
						$agency_name = $agency->agency_name;

				?>
					<option value='<?= $agency_code ?>'><?= $agency_name ?></option><?php
																				}
																					?>
			</select>
		</div>
		<div class="form-group">
			<label>Company</label>
			<input class="form-control" name="company" value="<?= $supplier['company'] ?>" readonly />
		</div>
	<?php } else { ?>
		<div class="form-group">
			<label>Company</label>
			<input type="hidden" name="agency" class="form-control" value="<?= $supplier['agency']; ?>">
			<input class="form-control" name="company" value="<?= $supplier['company'] ?>" readonly />
		</div>
	<?php } ?>
	<div class="form-group">
		<label>Promo Violation</label>
		<input type="file" id='files' class="form-control" name="files[]" multiple accept="image/png, image/jpeg" required="">
	</div>
<?php
			} else if ($request == "view_violation") {
?>
	<input type="hidden" name="pc_code" value="<?= $fetch['pc_code'] ?>">
	<input type="hidden" name="cutoff" value="<?= $fetch['cutoff'] ?>">
	<input type="hidden" name="server" value="<?= $fetch['server'] ?>">
	<input type="hidden" name="dateFrom" value="<?= $fetch['dateFrom'] ?>">
	<input type="hidden" name="dateTo" value="<?= $fetch['dateTo'] ?>">
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
			<img src="<?= base_url() . '' . $violation[0]->image_path ?>" width="100%" loading="lazy">
		</div>
	</div>
	<?php
			} else if ($request == 'supplier_chat_messages') {

				foreach ($email as $id) :

					$msg = $this->email_model->message_details($id);

					$userId = $msg->userId;
					$feedback = '';
					if ($msg->cc != 0) {
						$userId = $msg->cc;
						$feedback = 'Admin: ';
					}

					$photo = $this->email_model->view_picture($userId);
					$active = $this->email_model->active_chat($userId);
	?>


		<div id="<?= $userId ?>" class="chat_list <?= $active ?>" onclick="chat_list(<?= $userId ?>)">
			<div class="chat_people">
				<div class="chat_img"> <img src="<?= base_url() . '../' . $photo['photo']; ?>" alt="sender"> </div>
				<div class="chat_ib">
					<h5>
						<?php

						if (strlen($msg->sender) > 28) {

							echo substr($msg->sender, 0, 28) . "...";
						} else {

							echo $msg->sender;
						}
						?>
						<span class="chat_date">
							<?php

							if (date('Y-m-d', strtotime($msg->date_submitted)) == date('Y-m-d')) {
								echo date('h:i A', strtotime($msg->date_submitted));
							} else {
								echo date('m/d/y', strtotime($msg->date_submitted));
							}
							?>
						</span>
					</h5>
					<?php

					if ($msg->admin_read == 1) {

						if (strlen($msg->message) > 80) {

							echo $feedback . '' . substr($msg->message, 0, 80) . "...";
						} else {

							echo $feedback . '' . $msg->message;
						}
					} else {

						if (strlen($msg->message) > 80) {

							echo '<p class="sender_msg thick"><i id="sign_' . $userId . '" class="fa fa-circle text-info"></i> ' . $feedback . '' . substr($msg->message, 0, 80) . "...</p>";
						} else {

							echo '<p class="sender_msg thick"><i id="sign_' . $userId . '" class="fa fa-circle text-info"></i> ' . $feedback . '' . $msg->message . '</p>';
						}
					}
					?>
				</div>
			</div>
		</div><?php
				endforeach;
			} else if ($request == 'view_supplier_messages') { ?>

	<input type="hidden" name="cc" value="<?= $user_id ?>"> <?php

															foreach ($messages as $msg) :

																if ($msg->userId == $user_id) :

																	$photo = $this->email_model->view_picture($msg->userId); ?>

			<div class="incoming_msg">
				<div class="incoming_msg_img"> <img src="<?= base_url() . '../' . $photo['photo']; ?>" alt="sender"> </div>
				<div class="received_msg">
					<div class="received_withd_msg">
						<p>
							<?= $msg->message ?>
						</p>
						<span class="time_date"> <?= date('h:i A', strtotime($msg->date_submitted)) ?> | <?php if (date('Y-m-d') == date('Y-m-d', strtotime($msg->date_submitted))) : echo 'Today';
																											else : echo date('F d, Y', strtotime($msg->date_submitted));
																											endif; ?></span>
					</div>
				</div>
			</div> <?php

																else : ?>

			<div class="outgoing_msg">
				<div class="sent_msg">
					<p>
						<?= $msg->message ?>
					</p>
					<span class="time_date"> <?= date('h:i A', strtotime($msg->date_submitted)) ?> | <?php if (date('Y-m-d') == date('Y-m-d', strtotime($msg->date_submitted))) : echo 'Today';
																										else : echo date('F d, Y', strtotime($msg->date_submitted));
																										endif; ?></span>
				</div>
			</div><?php
																endif;
															endforeach;
														} else if ($request == 'edit_tag_vendor') {
					?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Supplier:</label>
				<input type="text" name="supplier" class="form-control" value="<?= $supplier->supplier ?>" readonly="">
			</div>
		</div>
		<div class="col-md-6"></div>
	</div>
	<input type="hidden" name="supplier_id" value="<?= $supplier->id ?>">
	<div class="row">
		<div class="col-md-12">
			<label>Select Vendor(s):</label>
			<div class="table-responsive">
				<table id="dt-editvendors" class="table table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Vendor Code</th>
							<th>Vendor Name</th>
						</tr>
					</thead>
					<tbody>
						<?php
															foreach ($vendors as $v) {

						?>
							<tr>
								<td><input type="checkbox" name="editvendor_id[]" value="<?= $v->vendor_id ?>" <?php if (in_array($v->vendor_code, $vendor_code)) {
																													echo 'checked';
																												}  ?> style="width:20px; height:20px;"></td>
								<td><?= $v->vendor_code ?></td>
								<td><?= $v->vendor_name ?></td>
							</tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			setTimeout(() => {
				$('#dt-editvendors').DataTable({
					"scrollY": "180px",
					"scrollCollapse": true,
					"paging": false
				});
			}, 500);

		});
	</script>
<?php
														}

?>