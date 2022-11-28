<!-- <style>
.dataTables_scroll .dataTables_scrollBody{
	padding: 30px 0px !important;
}

#salesTable_wrapper table tbody tr {
    padding: 25px 0px !important;
    height: 120px !important;
}

</style> -->
<div class="card-box mb-30">
						<div class="pd-20">
							<h4 class="text-blue h4">{{ $title }}</>
							<a class="btn btn-primary pull-right" href="{{ $addBtn }}" role="button">Add</a>
							
							</div>
						<div class="pb-20">
							<table class="table stripe hover nowrap" id="salesTable"  width="100%">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">Name</th>
										<th>email</th>
										<th>mobile</th>
										<th>status</th>
										<th class="datatable-nosort">Action</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>