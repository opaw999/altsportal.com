<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;">Admin</a></li>
		<li class="breadcrumb-item active">User Account</li>
	</ol>
	<h1 class="page-header">Admin <small>(user Account)</small></h1>
	<div class="panel panel-inverse">
		<div class="panel-heading" style="display: flex; align-items: center; justify-content: space-between;">
			<h4 class="panel-title">Change Account Details</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<form id="data_username" data-parsley-validate="true">
						<div class="note alert-gray-500 mb-3">
							<div class="note-content">
								<h4><b>Note!</b></h4>
								<p><code>Username should be unique</code>, must contain only letters, numbers and underscores and at least 5 or more characters.</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mb-2">
								<label class="form-label">Current Username</label>
								<input type="text" name="current_username" class="form-control" data-parsley-minlength="5" required autocomplete="off">
							</div>
							<div class="col-md-12 mb-2">
								<label class="form-label">New Username</label>
								<input type="text" name="new_username" class="form-control" data-parsley-minlength="5" required autocomplete="off">
							</div>
							<div class="col-md-12 mb-2">
								<label class="form-label">Re-type New Username</label>
								<input type="text" name="retype_username" class="form-control" data-parsley-minlength="5" data-parsley-equalto="[name = 'new_username']" required autocomplete="off">
							</div>
							<div class="col-md-12 d-flex justify-content-end">
								<button type="submit" class="btn btn-primary">Submit Username</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6">
					<form id="data_password" data-parsley-validate="true">
						<div class="note alert-gray-500 mb-3">
							<div class="note-content">
								<h4><b>Note!</b></h4>
								<p><code>Password is alphanumeric</code>. Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters.</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mb-2">
								<label class="form-label">Current Password</label>
								<input type="password" name="current_password" class="form-control" data-parsley-pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-parsley-type="alphanum" required data-parsley-minlength="8">
							</div>
							<div class="col-md-12 mb-2">
								<label class="form-label">New Password</label>
								<input type="password" name="new_password" class="form-control" data-parsley-pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-parsley-type="alphanum" required data-parsley-minlength="8">
							</div>
							<div class="col-md-12 mb-2">
								<label class="form-label">Re-type New Password</label>
								<input type="password" name="retype_password" class="form-control" data-parsley-pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-parsley-type="alphanum" required data-parsley-minlength="8" data-parsley-equalto="[name = 'new_password']">
							</div>
							<div class="col-md-12 d-flex justify-content-end">
								<button type="submit" class="btn btn-primary">Submit Password</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>