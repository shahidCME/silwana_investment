<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="userProfileForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>First Name</label>
                <input class="form-control" name="fname" type="text" placeholder="Enter fname" value="{{ $profileData[0]->fname }}">
                <label id="fname-error" class="error" for="fname">@error('fname') {{ $message}} @enderror</label>
            </div>
            <div class="form-group">
                <label >DOB</label>
                <input class="form-control date-picker" name="dob" placeholder="Select Date" type="text" autocomplete="off" value="{{ date('d F Y',strtotime($profileData[0]->dob)) }}">
                <label id="dob-error" class="error" for="dob">@error('dob') {{ $message }}  @enderror</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Last Name</label>
                <input class="form-control" name="lname" type="text" placeholder="Enter lname" value="{{ $profileData[0]->lname }}">
                <label id="lname-error" class="error" for="lname">@error('lname') {{ $message}} @enderror</label>
            </div>
            <div class="form-group">
                <label>Select Country Code</label>
                    <select class="custom-select2 form-control"  name="country_code"  style="width: 100%; height: 38px">
                        <option value="">Select country code</option>
                        @foreach (GetDialcodelist() as $code => $country_name)
                        <option value="{{ $code }}" {{ ($code == $profileData[0]->country_code) ? "SELECTED" : "" }}>{{$country_name}}</option>
                        @endforeach
                    </select>
                <label id="country_code-error" class="error" for="country_code">@error('country_code') {{ $message }} @enderror</label>
            </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
                <label>Mobile Number</label>
                <input class="form-control" name="mobile" type="text" placeholder="mobile number" value="{{ $profileData[0]->mobile }}">
                <label id="mobile-error" class="error" for="mobile">@error('mobile') {{ $message }}  @enderror</label>
            </div>
            <div class="form-group">
                <label class="weight-600">Gender</label>
                <div class="custom-control custom-radio mb-5">
                    <input type="radio" id="gender1" name="gender" value="0" class="custom-control-input" {{ ($profileData[0]->gender == '0') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="gender1">Male</label>
                </div>
                <div class="custom-control custom-radio mb-5">
                    <input type="radio" id="gender2" name="gender" value="1" class="custom-control-input" {{ ($profileData[0]->gender == '1') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="gender2">Female</label>
                </div>
                <div class="custom-control custom-radio mb-5">
                    <input type="radio" id="gender3" name="gender" value="2" class="custom-control-input" {{ ($profileData[0]->gender == '2') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="gender3">Other</label>
                </div>
            </div>
        </div>
    </div>
        <div class="form-group ">
            <div class="btn-list">
                <button type="submit" id="btnSubmit" class="btn btn-success">Update</button>
                <a class="btn btn-danger" href="{{ $cancelBtn }}" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>