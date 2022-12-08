<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">{{ $title }}</> 
    </div>
    <div class="pb-20">
        <table class="table stripe hover nowrap" id="datatable"  width="100%">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">S.no</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th class="datatable-nosort">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contract as $key => $value)
                <tr>
                    <td class="table-plus datatable-nosort">{{ $key+1 ;}}</td>
                    <td>{{ date('d F Y',strtotime($value->start_date))}}</td>
                    <td>{{ date('d F Y',strtotime($value->end_date))}}</td>
                    <td class="datatable-nosort">
                    @if($value->contract_pdf != '')
                     <a href="{{ url('uploads/contract_pdf/'.$value->contract_pdf) }}" target="_blank" class="badge badge-success" ><i class="fa fa-download"></i></a> 
                    @endif
                    </td>
                </tr>      
                @endforeach
            </tbody>
        </table>
    </div>
</div>