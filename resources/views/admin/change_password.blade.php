<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="changePass" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Old Password</label>
                <input class="form-control" name="old_password" type="password" placeholder="Old password" value="">
                <label id="old_password-error" class="error" for="old_password">@error('old_password') {{ $message}} @enderror</label>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input class="form-control" name="password" type="password" id="password" placeholder="password number" value="">
                <label id="password-error" class="error" for="password">@error('password') {{ $message }}  @enderror</label>
            </div>
            <div class="form-group">
                <label class="col-sm-6 col-md-6 col-form-label">Confirm Password</label>
                <input class="form-control" name="confirm_password" placeholder="Confirm password" type="password" autocomplete="off" value="">
                <label id="confirm_password-error" class="error" for="confirm_password">@error('confirm_password') {{ $message }}  @enderror</label>
            </div>
        </div>
        <div class="col-md-6">
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