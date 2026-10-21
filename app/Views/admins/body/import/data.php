<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;">Import</a></li>
		<li class="breadcrumb-item active">Database</li>
	</ol>
	<h1 class="page-header">Import <small>(Database)</small></h1>
	<div class="panel panel-inverse">
		<div class="panel-heading" style="display: flex; align-items: center; justify-content: space-between;">
			<h4 class="panel-title">File Upload</h4>
		</div>
		<div class="panel-body">

			<form id="data_import" enctype="multipart/form-data" autocomplete="off" data-parsley-validate="true">
				<div class="row">
					<div class="col-md-6 mb-2">
						<div class="note alert-warning mb-2">
							<div class="note-icon"><i class="fa fa-lightbulb"></i></div>
							<div class="note-content">
								<h4><b>Note:</b></h4>
								<p>Only zip/rar file are allowed.</p>
							</div>
						</div>
					</div>
					<div class="col-md-6"></div>
					<div class="col-md-4">
						<input type="file" class="form-control" name="imported_data" accept=".zip,.rar" required>
					</div>
					<div class="col-md-2">
						<button type="submit" class="btn btn-primary w-100">Import Data</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>