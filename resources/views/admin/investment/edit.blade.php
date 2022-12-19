<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="investmentForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <input type="hidden" value="{{ $update_id }}" name="update_id" >
            <input type="hidden" value="{{ $editData[0]->admin_id }}" name="admin_id" >
            <input type="hidden" value="{{ $editData[0]->contract_pdf }}" name="edit_contract_pdf" >
            <div class="col-md-6">
                <div class="form-group">
                <label>Select Client</label>
                <select class="custom-select2 form-control"  name="customer"  style="width: 100%; height: 38px">
                    <option value="">Select Client</option>
                    @foreach ($getCustomer as $item)
                    <option value="{{ $item->id}}" {{ ($editData[0]->user_id == $item->id) ? 'SELECTED':'' }}>{{ $item->fname.' '.$item->lname }}</option>
                    @endforeach
                    </select>
                    <label id="customer-error" class="error" for="customer">@error('customer') {{ $message }} @enderror</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Investment Plan</label>
                    <select class="custom-select2 form-control"  name="schema"  style="width: 100%; height: 38px">
                        <option value="">Select Plan</option>
                        @foreach ($getSchema as $schem)    
                        <option value="{{ $schem->id}}" {{ ($editData[0]->schema_id == $schem->id) ? 'SELECTED':'' }}>{{ $schem->name}}</option>
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
                <input class="form-control" name="amount" type="text" placeholder="Enter investment amount" value="{{ $editData[0]->amount}}">
                <label id="amount-error" class="error" for="amount">@error('amount') {{ $message}} @enderror</label>
            </div>
            <div class="form-group">
                <label>Tenure</label>
                <input class="form-control" name="tenure" type="text" value="{{ $editData[0]->tenure}}" placeholder="Enter tenure">
                <label id="tenure-error" class="error" for="tenure">@error('tenure') {{ $message }} @enderror</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Start Date</label>
                <input class="form-control date-picker" name="start_date" placeholder="Select Date" type="text" value="{{ date('d F Y',strtotime($editData[0]->start_date))}}" autocomplete="off">
                <label id="start_date-error" class="error" for="start_date">@error('start_date') {{ $message }}  @enderror</label>
            </div>
            <div class="form-group">
                <label>Return Percentage(%)</label>
                <input class="form-control" name="return_percentage" type="text" placeholder="Enter return percentage" value="{{ $editData[0]->return_percentage}}">
                <label id="return_percentage-error" class="error" for="return_percentage">@error('rereturn_percentage') {{ $message }}  @enderror</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="weight-600">Return Type</label>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="returnType1" name="return_type" value="0" class="custom-control-input" {{ ($editData[0]->return_type == '0') ? 'checked' : '' }}>
                <label class="custom-control-label" for="returnType1">Monthly</label>
            </div>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="returnType2" name="return_type" value="1" class="custom-control-input" {{ ($editData[0]->return_type == '1') ? 'checked' : '' }}>
                <label class="custom-control-label" for="returnType2">Yearly</label>
            </div>
        </div>
        @if(admin_login()['role'] == '1' || admin_login()['role'] == '4')
        <div class="col-md-6">
            <label class="weight-600">Status</label>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="status1" name="status" value="0" class="custom-control-input" {{ ($editData[0]->status == 0)? "checked" :"" }}>
                <label class="custom-control-label" for="status1">Rejecte</label>
            </div>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="status2" name="status" value="1" class="custom-control-input" {{ ($editData[0]->status == 1)? "checked" :"" }}>
                <label class="custom-control-label" for="status2">Approve</label>
            </div>
            <div class="custom-control custom-radio mb-5">
                <input type="radio" id="status3" name="status" value="2" class="custom-control-input" {{ ($editData[0]->status == 2)? "checked" :"" }}>
                <label class="custom-control-label" for="status3">Pending</label>
            </div>
        </div>
        @endif
    </div>
        @if(admin_login()['role'] == '4' || admin_login()['role'] == '1')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Contract</label>
                    <select class="custom-select2 form-control"  name="contract"  style="width: 100%; height: 38px">
                        <option value="">Select Contract</option>
                        @foreach (getContractTemplate() as $template => $item)
                        <option value="{{ $template }}"  {{ ( $editData[0]->contract_type != NULL && strtolower($editData[0]->contract_type) == $template ) ? 'selected': '' }}>{{$item}}</option>
                        @endforeach
                    </select>
                    <label id="contract-error" class="error" for="contract"></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Language</label>
                    <select class="custom-select2 form-control"  name="lang"  style="width: 100%; height: 38px">
                        <option value="">Select Language</option>
                        <option value="0"  {{ ($editData[0]->language == "0" ) ? 'selected': '' }} >English</option>
                        <option value="1"  {{ ($editData[0]->language == "1" ) ? 'selected': '' }} >Arabic</option>
                    </select>
                    <label id="contract-error" class="error" for="contract"></label>
                </div>
            </div>
        </div>
        <div class="form-group"  style="display:none">
            <label>Contract Reciept</label>
            <input type="hidden" name="old_contract_reciept" value="{{ $editData[0]->contract_reciept }}">
            <input type="file" name="edit_contract_reciept" class="form-control-file form-control height-auto file-upload-input" onchange="readURL(this);" accept="contract_reciept/*" />
            <div class="file-upload-content " style="display: {{ ($editData[0]->contract_reciept == '') ? 'none' : '' }}">
                <img class="file-upload-image" src="{{ url('uploads/contract_reciept/'.$editData[0]->contract_reciept) }}" alt="your contract_reciept" />
            </div>
        </div>
        <div class="form-group" style="display:none">
            <label>Investment document</label>
            <input type="hidden" name="old_invest_document" value="{{ $editData[0]->investment_doc }}">
            <input type="file" name="edit_invest_document" class="form-control-file form-control height-auto " />
        </div>
        <div class="form-group" style="display:none">
            <label>Other document</label>
            <input type="hidden" name="old_other_document" value="{{ ($editData[0]->other_doc != '') ? $editData[0]->other_doc : '' }}">
            <input type="file" name="edit_other_document" class="form-control-file form-control height-auto " />
        </div>
    @endif
        <div class="form-group ">
            <div class="btn-list">
                <button type="submit" id="btnSubmit" class="btn btn-success">Add</button>
                <a class="btn btn-danger" href="{{ url('Investment') }}" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>