<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="investmentForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                <label>Select Customer</label>
                <select class="custom-select2 form-control"  name="customer"  style="width: 100%; height: 38px">
                    <option value="">Select Customer</option>
                    @foreach ($getCustomer as $item)
                    <option value="{{ $item->id}}">{{ $item->fname.' '.$item->lname }}</option>
                    @endforeach
                    </select>
                    <label id="customer-error" class="error" for="customer">@error('customer') {{ $message }} @enderror</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Schema</label>
                    <select class="custom-select2 form-control"  name="schema"  style="width: 100%; height: 38px">
                        <option value="">Select Schema</option>
                        @foreach ($getSchema as $schem)    
                        <option value="{{ $schem    ->id}}">{{ $schem->name}}</option>
                        @endforeach
                    </select>
                    <label id="schema-error" class="error" for="schema">@error('schema') {{ $message }} @enderror</label>
                </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Amount</label>
                <input class="form-control" name="amount" type="text" placeholder="Enter investment amount">
                <label id="amount-error" class="error" for="amount">@error('amount') {{ $message}} @enderror</label>
            </div>
            <div class="form-group">
                <label>Tenure</label>
                <input class="form-control" name="tenure" type="text" placeholder="Enter tenure">
                <label id="tenure-error" class="error" for="tenure">@error('tenure') {{ $message }} @enderror</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-6 col-md-6 col-form-label">Start date</label>
                <input class="form-control date-picker" name="start_date" placeholder="Select Date" type="text" autocomplete="off">
                <label id="start_date-error" class="error" for="start_date">@error('start_date') {{ $message }}  @enderror</label>
            </div>
            <div class="form-group">
                <label>return percentage(%)</label>
                <input class="form-control" name="return_percentage" type="text" placeholder="Enter return percentage" >
                <label id="return_percentage-error" class="error" for="return_percentage">@error('rereturn_percentage') {{ $message }}  @enderror</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="weight-600">Return type</label>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="returnType1" name="return_type" value="0" class="custom-control-input" checked>
                <label class="custom-control-label" for="returnType1">monthly</label>
            </div>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="returnType2" name="return_type" value="1" class="custom-control-input" >
                <label class="custom-control-label" for="returnType2">yearly</label>
            </div>
        </div>
        @if(admin_login()['role'] == '1' || admin_login()['role'] == '4')
        <div class="col-md-6">
            <label class="weight-600">Status</label>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="status1" name="status" value="0" class="custom-control-input"  >
                <label class="custom-control-label" for="status1">Rejecte</label>
            </div>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="status2" name="status" value="1" class="custom-control-input" checked>
                <label class="custom-control-label" for="status2">Approve</label>
            </div>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="status3" name="status" value="2" class="custom-control-input" >
                <label class="custom-control-label" for="status3">Pending</label>
            </div>
        </div>
        @endif
    </div>
    @if(admin_login()['role'] == '4' || admin_login()['role'] == '1')
        <div class="form-group" style="display:none">
            <label>Contract Reciept</label>
            <input type="file" name="contract_reciept" class="form-control-file form-control height-auto file-upload-input" onchange="readURL(this);" accept="/*" />
            <div class="file-upload-content " style="display: none">
                <img class="file-upload-image" src="" alt="your contract_reciept" />
            </div>
        </div>
        <div class="form-group" style="display:none">
            <label>Investment document</label>
            <input type="file" name="invest_document" class="form-control-file form-control height-auto " />
        </div>
        <div class="form-group" style="display:none">
            <label>Other document</label>
            <input type="file" name="other_document" class="form-control-file form-control height-auto " />
        </div>
    @endif
        {{-- <div class="form-group" >
            <div class="row">
                <div class="col-md-4 ">
                    <label class="weight-600">Status</label>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status1" name="status" value="0" class="custom-control-input" checked>
                        <label class="custom-control-label" for="status1">Rejected</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status2" name="status" value="1" class="custom-control-input" >
                        <label class="custom-control-label" for="status2">Approved</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status3" name="status" value="1" class="custom-control-input" >
                        <label class="custom-control-label" for="status3">Pending</label>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="form-group ">
            <div class="btn-list">
                <button type="submit" id="btnSubmit" class="btn btn-success">Add</button>
                <a class="btn btn-danger" href="{{ url('Investment') }}" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>