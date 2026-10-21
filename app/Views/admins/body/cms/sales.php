<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>	
	<h1 class="page-header"> CMS <small> Sales </small></h1>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4> Item Sales </h4>
		</div>
		<div class="panel-body">
			<table id="dt_sales" class="table table-bordered table-striped" width="100%">
				<thead>
					<tr>
						<th> ISR No </th>
						<th> Suf ID </th>
						<th> Vendor Code </th>
						<th> Comp Code </th>
						<th> Dept Code </th>
						<th> Start </th>
						<th> End </th>
						<th> NetSale </th>
					</tr>
				</thead>
			</table>
		</div>
	</div>		
</div>