<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="profileForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>First name</label>
                <input class="form-control" name="fname" type="text" placeholder="First name" value="{{ $profileData[0]->fname }}">
                <label id="fname-error" class="error" for="fname">@error('fname') {{ $message}} @enderror</label>
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
                <label class="col-sm-6 col-md-6 col-form-label">Last Name</label>
                <input class="form-control" name="lname" placeholder=" Last name" type="text" autocomplete="off" value="{{ $profileData[0]->lname }}">
                <label id="lname-error" class="error" for="lname">@error('lname') {{ $message }}  @enderror</label>
            </div>
            <div class="form-group">
                <label>mobile number</label>
                <input class="form-control" name="mobile" type="text" placeholder="Mobile number" value="{{ $profileData[0]->mobile }}">
                <label id="mobile-error" class="error" for="mobile">@error('mobile') {{ $message }}  @enderror</label>
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