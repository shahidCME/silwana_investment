<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">{{ $title }}</>
        @if(admin_login()['role'] != '3')
        <a class="btn btn-primary pull-right" href="{{ $addButton }}" role="button">Add</a>
        @endif    
    </div>
    <div class="pb-20">
        <table class="table stripe hover nowrap" id="InvestmentTable">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">Customer Fullname</th>
                    <th>Sales person</th>
                    <th>Schema</th>
                    <th>Start Date</th>
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