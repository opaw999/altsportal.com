<div id="content" class="app-content">
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>
	<h1 class="page-header"> CMS <small> Deductions </small></h1>	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4> Deductions </h4>
		</div>
		<div class="panel-body">
			<table id="dt_deduction" class="table table-bordered table-striped" width="100%">
				<thead>
					<tr>
						<th> No. </th>
						<th> Doc No. </th>
						<th> VenCode </th>
						<th> Vendor Name </th>
						<th> Company </th>
						<th> Dept </th>
						<th> Posting Date </th>
						<th> Details </th>
						<th> Amount </th>   
					</tr>
				</thead>
			</table>
		</div>
	</div>		
</div>