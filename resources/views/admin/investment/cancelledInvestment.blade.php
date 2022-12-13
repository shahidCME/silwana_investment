<!-- <style>
    .dataTables_wrapper > .row:nth-child(2) > .table{
        width: 100% !important;
    }   
</style> -->

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">{{ $title }}</> 
    </div>
    <div class="pb-20">
        <table class="table stripe hover nowrap" id="datatable"  width="100%">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">Customer Fullname</th>
                    <th>Sales person</th>
                    <th>Investment Plan</th>
                    <th>Start Date</th>
                    <th>Cancel Date</th>
                    <th>Amount</th>
                    <th>Return Type</th>
                    <th class="datatable-nosort">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cancelledInvestment as $key => $value)
                <tr>
                    <td class="table-plus datatable-nosort">{{ $value->customerFname }} {{ $value->customerFname }}</td>
                    <td>{{ $value->fname }} {{ $value->lname }}</td>
                    <td>{{ $value->schema }}</td>
                    <td>{{ date('d F Y',strtotime($value->start_date))}}</td>
                    <td>{{ date('d F Y',strtotime($value->updated_at))}}</td>
                    <td>{{ $value->amount }}</td>
                    <td>{{ ($value->return_type =='0') ? "Monthly" : "Yearly"; }}</td>
                    <td class="datatable-nosort">
                    <a href="#" class="btn btn-warning btn-sm comment" data-comment ="{{ $value->contractCancelComment }}" data-toggle="modal" data-target="#Medium-modal" type="button">Comment</a>
                        <?php $btn = '<a href="'.url('cancelledRoi/'.encrypt($value->id)).'" class="btn btn-primary btn-sm">Roi</a>'; ?>
                        <?=$btn?>
                    </td>
                </tr>      
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-4 col-sm-12 mb-30" >
        <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Contract Cancelled Comment 
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
					    <p id="dynamic_comment"></p>
					</div>    
                </div>
            </div>
        </div>
</div>