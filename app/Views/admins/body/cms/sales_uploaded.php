<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>	
	<h1 class="page-header"> CMS <small> Sales Uploaded </small></h1>		
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4> Sales Uploaded </h4>
		</div>
		<div class="panel-body">
			<table id="dt_sales_uploaded" class="table table-bordered table-striped" width="100%">
				<thead>
					<tr>
						<th> Suf ID </th>
						<th> Terms </th>
						<th> Cutoff Start </th>
						<th> Cutoff End </th>
						<th> File name </th>
						<th> Date Uploaded </th>
						<th> Uploaded By</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>		
</div>