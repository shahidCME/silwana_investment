<div class="card-box  investment-card-box  mb-30 ">
    <div class="pd-20">
        <h4 class="text-blue h4">{{ $title }} </>
        @if(admin_login()['role'] != '3')
        <a class="btn btn-primary pull-right" href="{{ $addButton }}" role="button">Add</a>
        @endif    
    </div>
    <div class="pb-20 investment-card-table">
        
        <table class="table stripe hover nowrap " id="InvestmentTable" width="100%">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">Customer Fullname</th>
                    <th>Sales person</th>
                    <th>Investment Plan</th>
                    <th>Start Date</th>
                    <!-- <th>End Date</th> -->
                    <th>Amount</th>
                    <th>Return Type</th>
                    <th>Status</th>
                    <th class="datatable-nosort">Action/Contract pdf </th>
                </tr>
            </thead>
            <tbody>
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
                            Contact Cancel 
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="contractCancelForm" action="{{ url('contractCancel');}}", method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Comment</label>
                            <textarea class="form-control" name="contractCancelComment"></textarea>
                        </div>
                        <input type="hidden" name="investment_id" id="investment_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">Add</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
</div>