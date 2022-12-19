<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="salesPerson" action="{{ $action }}" method="POST">
        @csrf
        <input type="hidden" value="{{ $update_id }}" name="update_id">
        <div class="form-group">
            <label>First Name</label>
            <input class="form-control" name="fname" type="text" value="{{ $editData[0]->fname }}" placeholder="Enter first name">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input class="form-control" name="lname" type="text" value="{{ $editData[0]->lname }}" placeholder="Enter last name">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input class="form-control" name="email"  type="email" value="{{ (old('email')) ? old('email') : $editData[0]->email }}" placeholder="Enter email">
            <label id="email-error" class="error" for="email">@error('email') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input class="form-control" name="password" value="123456" type="password" disabled>
        </div>
        <div class="form-group">
            <label>Mobile</label>
            <input class="form-control" name="mobile" value="{{ $editData[0]->mobile }}" type="text" placeholder="Enter mobile">
        </div>
        <div class="form-group">
            <div class="row" >
                <div class="col-md-4 " style="display:none">
                    <label class="weight-600">Role</label>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="role1" name="role" value="1" class="custom-control-input" {{ ($editData[0]->role ==1)?"checked" :"" }}>
                        <label class="custom-control-label" for="role1">Admin</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="role2" name="role" value="0" class="custom-control-input" {{ ($editData[0]->role ==0)?"checked" :"" }}>
                        <label class="custom-control-label" for="role2">SalesPerson</label>
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
        {{-- <div class="form-group">
            <label class="col-sm-12 col-md-2 col-form-label">DOB</label>
                <input class="form-control date-picker" name="dob" placeholder="Select Date" type="text">
        </div> --}}
        {{-- <div class="form-group">
            <label>Example file input</label>
            <input type="file" class="form-control-file form-control height-auto">
        </div> --}}
        {{-- <div class="form-group">
            <label>Custom file input</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input">
                <label class="custom-file-label">Choose file</label>
            </div>
        </div> --}}
        <div class="form-group ">
            <div class="btn-list">
                <button type="submit" id="btnSubmit" class="btn btn-success">Update</button>
                <a class="btn btn-danger" href="{{ url('salesPerson') }}" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>