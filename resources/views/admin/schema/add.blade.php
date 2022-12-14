<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="schemaForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" name="name" type="text" placeholder="Enter name" id="name">
            <label id="name-error" class="error" for="name">@error('name') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Type</label>
            <input class="form-control" name="type"  type="type" placeholder="Enter type">
            <label id="type-error" class="error" for="type">@error('type') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Start Date</label>
            <input class="form-control date-picker" name="start_date" placeholder="Select Date" type="text" autocomplete="off">
        </div>
        <div class="form-group"> 
                <label>Details</label>
                <textarea class="form-control textarea_editor" name="details"></textarea>
        </div>
        <div class="form-group">
            <div class="row">
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
            <label>Image</label>
            <input type="file" name="image" class="form-control-file form-control height-auto file-upload-input" onchange="readURL(this);" accept="image/*" />
            <div class="file-upload-content " style="display: none">
                <img class="file-upload-image" src="" alt="your image" />
            </div>
        </div>
        <div class="form-group">
            <label>Plan Document</label>
            <input type="file" name="schema_document" class="form-control-file form-control height-auto " />
        </div>
        <div class="form-group ">
            <div class="btn-list">
                <button type="submit" id="btnSubmit" class="btn btn-success">Add</button>
                <a class="btn btn-danger" href="{{ $cancelBtn }}" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>