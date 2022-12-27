<div class="card-box mb-30">
    <div class="pd-20 d-flex align-items-center">
        <h4 class="text-blue h4 mb-0">{{ $title }}</h4>
        <p class="ml-2 mb-0" style="font-size:20px;font-weight:bold"> -> {{ ucwords($customer_name) }}</p>    
    </div>
    <div class="pb-20">
        <table class="table stripe hover nowrap" id="datatable"  width="100%">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">S.no</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <!-- <th>Terminate Date</th> -->
                    <th class="datatable-nosort">Payment File</th>
                    <th class="datatable-nosort">Signed Contract File</th>
                    <th >Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contract as $key => $value)
                <tr>
                    <td class="table-plus datatable-nosort">{{ $key+1 ;}}</td>
                    <td>{{ date('d F Y',strtotime($value->start_date))}}</td>
                    <td>{{ date('d F Y',strtotime($value->end_date))}}</td>
                    <!-- <td>{{ ($value->terminate_date != NULL ) ? date('d F Y',strtotime($value->terminate_date)) : '-' }}</td> -->
                    <td class="datatable-nosort">
                    @if($value->payment_reciept != '')
                     <a href="{{ url('uploads/contract_reciept/'.$value->payment_reciept) }}" target="_blank" class="badge badge-success" ><i class="fa fa-download"></i></a> 
                    @endif
                    </td>
                    <td class="datatable-nosort">
                    @if($value->signed_contract_file != '')
                     <a href="{{ url('uploads/signed_contract_file/'.$value->signed_contract_file) }}" target="_blank" class="badge badge-success" ><i class="fa fa-download"></i></a> 
                    @endif
                    </td>
                    <td>{{ date('d F Y H:i:s',strtotime($value->created_at))}}</td>
                </tr>      
                @endforeach
            </tbody>
        </table>
    </div>
</div>