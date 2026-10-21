<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>
	<h1 class="page-header"> CMS <small> Check Monitoring </small></h1>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>  Check Monitoring  </h4>
		</div>
		<div class="panel-body">
			<table id="dt_checks" class="table table-bordered table-striped" width="100%">
				<thead>
					<tr>  
						<th> CV ID </th>
						<th> CV NO </th>
						<th> CV DATE </th>
						<th> CV STATUS </th>
						<th> TOTAL PAID AMOUNT </th>
						<th> VENDOR CODE </th>
						<th> VENDOR NAME </th>
						<th> COLLECTOR NAME </th>
						<th> BANK ACCT </th>
					</tr>
				</thead>
			</table>
		</div>
	</div>		
</div>