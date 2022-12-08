<style>
    label.custom{
        color:red;
    }
    </style>
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="userForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>First Name</label>
            <input class="form-control" name="fname" type="text" placeholder="Enter first name">
            <label id="fname-error" class="error" for="fname">@error('fname') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input class="form-control" name="lname" type="text" placeholder="Enter last name">
            <label id="lname-error" class="error" for="lname">@error('lname') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input class="form-control" name="email"  type="email" placeholder="Enter email">
            <label id="email-error" class="error" for="email">@error('email') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input class="form-control" name="password" value="123456" type="password">
        </div>
        <div class="form-group">
            <label>Select Country</label>
            <select class="custom-select2 form-control" id="country_id"  name="country"  style="width: 100%; height: 38px">
                <option value="">Select country</option>
                @foreach ($countries as $item)
                <option value="{{ $item->id}}">{{ $item->name}}</option>
                @endforeach
            </select>
            <label id="country_id-error" class="error" for="country_id">@error('country') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Nationality</label>
            <input class="form-control" name="nationality" id="nationality" value="" type="text" placeholder="Enter Nationality">
            <label id="nationality-error" class="error" for="nationality">@error('nationality') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Mobile</label>
            <input class="form-control" name="mobile" value="" type="text" placeholder="Enter mobile">
            <label id="mobile-error" class="error" for="mobile">@error('mobile') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <label class="weight-600">Gender</label>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="gender1" name="gender" value="0" class="custom-control-input" checked>
                        <label class="custom-control-label" for="gender1">Male</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="gender2" name="gender" value="1" class="custom-control-input">
                        <label class="custom-control-label" for="gender2">Female</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="gender3" name="gender" value="2" class="custom-control-input">
                        <label class="custom-control-label" for="gender3">other</label>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <label class="weight-600">Status</label>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status1" name="status" value="1" class="custom-control-input" checked>
                        <label class="custom-control-label" for="status1">Active</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status2" name="status" value="0" class="custom-control-input" >
                        <label class="custom-control-label" for="status2">Inactive</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>DOB</label>
            <input class="form-control date-picker_dob" name="dob" placeholder="Select Date" type="text" autocomplete="off">
        </div>
        <div class="form-group">
            <label>National Id Number</label>
            <input class="form-control" name="national_id" placeholder="enter national id number" type="text" autocomplete="off">
        </div>
        <div class="form-group">
            <label>National Id Valid Thru</label>
            <input class="form-control date-picker_dob" name="date_of_expiry" placeholder="select date valid thru" type="text" autocomplete="off">
        </div>
        
        <div class="form-group">
            {{-- <label class="weight-600">KYC Document</label> --}}
            <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" name="is_kyc" value="1" class="custom-control-input is_kyc" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">KYC Details</label>
                </div>
        </div>
        <div id="kycForm" style="display: none" >
            <div class="form-group" id="append_html">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label>Name Document</label>
                            <input class="form-control name_document" name="name_document[]" type="text" placeholder="Enter document name">
                            <label id="name_document-error" class="custom" for="name_document">@error('name_document') {{ $message }} @enderror</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>Valid From</label>
                            <input type="text" class="form-control valid_from date-picker" name="valid_from[]" placeholder="Select valid from">
                            <label class="" for="valid_from"></label>
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>Valid Thru</label>
                            <input type="text" class="form-control valid_thru date-picker" name="valid_thru[]" placeholder="Select valid thru">
                            <label class="" for="valid_from"></label>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label>Documents</label>
                            <input type="file" class="form-control document_file" name="document_file[]">
                            <label class="custom" for="document_file"></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>Action</label>
                        <button type="button" class="btn btn-primary add_more d-block" value="Add"><i class="icon-copy fa fa-plus" aria-hidden="true"></i></button>
                    </div> 
                </div>
            </div>
        </div>
        <div class="form-group ">
            <div class="btn-list">
                <button type="submit" id="btnSubmit" class="btn btn-success">Add</button>
                <a class="btn btn-danger" href="{{ url('customer') }}" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>

<div style="display:none" id="appended">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            
                            <input class="form-control name_document" name="name_document[]" type="text" placeholder="Enter document name">
                            <label id="name_document-error" class="custom" for="name_document"></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control date-picker" name="valid_from[]" placeholder="Select valid from">
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control date-picker" name="valid_thru[]" placeholder="Select valid thru">
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <input type="file" class="form-control document_file" name="document_file[]">
                            <label class="custom" for="document_file"></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove d-block" ><i class="icon-copy fa fa-minus" aria-hidden="true"></i></button>
                    </div> 
                </div>
            </div>
</div>