<div id="content"  class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>	
	<h1 class="page-header"> CMS <small> Items </small></h1>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4> Items </h4>
		</div>
		<div class="panel-body">
			<table id="dt_items" class="table table-bordered table-striped" width="100%">
				<thead>
					<tr>
						<th> No. </th>
						<th> Item Code </th>
						<th> Item Description </th>
						<th> Vendor Code </th>
						<th> Vendor Name </th>
						<th> Comm Rate </th>
						<th> Date Uploaded </th>
					</tr>
				</thead>
			</table>
		</div>
	</div>		
</div>		