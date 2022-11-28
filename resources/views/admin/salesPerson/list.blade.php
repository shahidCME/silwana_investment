<div class="card-box mb-30">
						<div class="pd-20">
							<h4 class="text-blue h4">{{ $title }}</>
							<a class="btn btn-primary pull-right" href="{{ url('add_sales_person') }}" role="button">Add</a>
							
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
									<tr>
										<td class="table-plus">Gloria F. Mead</td>
										<td>25</td>
										<td>Sagittarius</td>
										<td>2829 Trainer Avenue Peoria, IL 61602</td>
										<td>
											<div class="dropdown">
												<a
													class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
													href="#"
													role="button"
													data-toggle="dropdown"
												>
													<i class="dw dw-more"></i>
												</a>
												<div
													class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
												>
													<a class="dropdown-item" href="#"
														><i class="dw dw-eye"></i> View</a
													>
													<a class="dropdown-item" href="#"
														><i class="dw dw-edit2"></i> Edit</a
													>
													<a class="dropdown-item" href="#"
														><i class="dw dw-delete-3"></i> Delete</a
													>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>