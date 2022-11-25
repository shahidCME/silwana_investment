<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="userForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden"  name="update_id" value="{{ $update_id }}">
        <div class="form-group">
            <label>First Name</label>
            <input class="form-control" name="fname" value="{{ $editData[0]->fname }}" type="text" placeholder="Enter first name">
            <label id="fname-error" class="error" for="fname">@error('fname') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input class="form-control" name="lname" type="text" value="{{ $editData[0]->lname }}" placeholder="Enter last name">
            <label id="lname-error" class="error" for="lname">@error('lname') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input class="form-control" name="email"  value="{{  $editData[0]->email }}" type="email" placeholder="Enter email">
            <label id="email-error" class="error" for="email">@error('email') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input class="form-control" name="password" value="123456"  type="password" disabled>
        </div>
        <div class="form-group">
            <label>Select Country</label>
            <select class="custom-select2 form-control" id="country_id"  name="country"  style="width: 100%; height: 38px">
                <option value="">Select country</option>
                @foreach ($countries as $item)
                <option value="{{ $item->id}}" {{ ($item->id == $editData[0]->country_id) ? 'selected': '' }}>{{ $item->name}}</option>
                @endforeach
            </select>
            <label id="country_id-error" class="error" for="country_id">@error('country') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Nationality</label>
            <input class="form-control" name="nationality" id="nationality" value="{{ $editData[0]->nationality }}" type="text" placeholder="Enter Nationality">
            <label id="nationality-error" class="error" for="nationality">@error('nationality') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Mobile</label>
            <input class="form-control" name="mobile"  value="{{ $editData[0]->mobile }}" type="text" placeholder="Enter mobile">
            <label id="mobile-error" class="error" for="mobile">@error('mobile') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 ">
                    <label class="weight-600">Gender</label>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="gender1" name="gender" value="0" class="custom-control-input" {{ ($editData[0]->gender == 0) ? 'checked': '' }} >
                        <label class="custom-control-label" for="gender1">Male</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="gender2" name="gender" value="1" class="custom-control-input" {{ ($editData[0]->gender == 1) ? 'checked': '' }}>
                        <label class="custom-control-label" for="gender2">Female</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="gender3" name="gender" value="2" class="custom-control-input" {{ ($editData[0]->gender == 2) ? 'checked': '' }}>
                        <label class="custom-control-label" for="gender3">other</label>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <label class="weight-600">Status</label>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status1" name="status" value="1" class="custom-control-input" {{ ($editData[0]->status == 1)? "checked" :"" }}>
                        <label class="custom-control-label" for="status1">Active</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status2" name="status" value="0" class="custom-control-input" {{ ($editData[0]->status == 0)? "checked" :"" }}>
                        <label class="custom-control-label" for="status2">Inactive</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-12 col-md-2 col-form-label">DOB</label>
                <input class="form-control date-picker" name="dob" placeholder="Select Date" value="{{ date('d F Y',strtotime($editData[0]->dob)) }}" type="text">
        </div>
        <div class="form-group">
            {{-- <label class="weight-600">KYC Document</label> --}}
            <div class="custom-control custom-checkbox mb-5">
                    <input type="checkbox" name="is_kyc" value="1" class="custom-control-input" id="customCheck1" {{ ($editData[0]->kyc == true) ? 'checked' : '' }} >
                    <label class="custom-control-label" for="customCheck1">KYC Details</label>
                </div>
        </div>
        <div id="kycForm" style="display: {{ ($editData[0]->kyc == true) ? 'block' : 'none' }}">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>National Id Number</label>
                            <input class="form-control" name="national_id" value="{{ (isset($editData[0]->kycData)) ? $editData[0]->kycData[0]->national_id : '' }}" type="text" placeholder="Enter name">
                            <label id="national_id-error" class="error" for="national_id">@error('nanational_idme') {{ $message }} @enderror</label>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" name="address">{{ (isset($editData[0]->kycData)) ? $editData[0]->kycData[0]->address : '' }}</textarea>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-12 col-md-2 col-form-label">Date Of Expiry</label>
                <input class="form-control date-picker" name="date_of_expiry" placeholder="Select Date" value="{{ (isset($editData[0]->kycData)) ? date('d F Y',strtotime($editData[0]->kycData[0]->date_of_expiry)) : '' }}" type="text">
        </div>
        <div class="form-group">
            <div class="col-md-8">
                <label>Natinal Id</label>
                <input type="hidden" name="old_image" value="{{ (isset($editData[0]->kycData)) ? $editData[0]->kycData[0]->nationalIdImage : '' }}">
                <input type="file" name="editnationalIdImage" class="form-control-file form-control height-auto">
            </div>
        </div>
        @if((isset($editData[0]->kycData)))
            <div style="width: 200px;hieght:200px">
                <img src="{{ url('uploads/national_id/'.$editData[0]->kycData[0]->nationalIdImage) }}" alt="" srcset="">
            </div>
        @endif
        
    </div>
        <div class="form-group ">
            <div class="btn-list">
                <button type="submit" id="btnSubmit" class="btn btn-success">Add</button>
                <a class="btn btn-danger" href="{{ url('customer') }}" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>