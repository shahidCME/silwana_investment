<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">{{ $title }}</>        
        </div>
    <div class="pb-20">
        <table class="table stripe hover nowrap" id="data-table" width="100%">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">S no.</th>
                    <th>Document Name</th>
                    <th>Valid From</th>
                    <th>Valid Thru</th>
                    <th class="datatable-nosort">Document File</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record as $key => $item)
                    
                <tr>
                    <td class="table-plus">{{$key+1}}</td>
                    <td>{{$item->name_document}}</td>
                    <td>{{ ($item->valid_from != NULL) ? date('d M Y',strtotime($item->valid_from)) : '-' }}</td>
                    <td>{{ ($item->valid_thru != NULL) ? date('d M Y',strtotime($item->valid_thru)) : '-' }}</td>
                    <td>
                        <a href='{{ url("uploads/kyc_document/".$item->document_file) }}' target="_blank" class="badge badge-success"><i class="fa fa-download"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>