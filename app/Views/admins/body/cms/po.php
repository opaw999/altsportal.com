<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>
	<h1 class="page-header"> CMS <small> PO </small></h1>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4> Purchase Order (P.0.) </h4>
		</div>
		<div class="panel-body">
			<table id="dt_po" class="table table-bordered table-striped" width="100%">
				<thead>
					<tr>
						<th> PO # </th>
						<th> VenCode </th>
						<th> Vendor Name </th>
						<th> Comp Code </th>
						<th> Dept Code </th>
						<th> Date Requested </th>
						<th> Status </th>
					</tr>
				</thead>
			</table>
		</div>
	</div>	
</div>