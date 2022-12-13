<style>
    .file-upload {
    width: 600px;
    padding: 20px;
}


.file-upload-image {
    max-height: 200px;
    max-width: 200px;
    margin: auto;
    padding: 20px;
}
</style>
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="text-blue h4">{{ $title }}</h4>
        </div>
    </div>
    <form id="schemaForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{ $update_id }}" name="update_id" >
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" name="name" type="text" placeholder="Enter name" value="{{ $editData[0]->name }}">
            <label id="name-error" class="error" for="name">@error('name') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Type</label>
            <input class="form-control" name="type"  type="type" placeholder="Enter type" value="{{ $editData[0]->type }}">
            <label id="type-error" class="error" for="type">@error('type') {{ $message }} @enderror</label>
        </div>
        <div class="form-group">
            <label>Start Date</label>
            <input class="form-control date-picker" name="start_date" placeholder="Select Date" type="text" value="{{ date("d F Y",strtotime($editData[0]->start_date)) }}">
        </div>
        <div class="form-group">
            <label>Details</label>
            <textarea class="form-control textarea_editor" name="details">{{ $editData[0]->details }}</textarea>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 ">
                    <label class="weight-600">Status</label>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status1" name="status" value="1" class="custom-control-input" {{ ($editData[0]->status==1) ? 'checked' : '' }} >
                        <label class="custom-control-label" for="status1">Active</label>
                    </div>
                    <div class="custom-control custom-radio mb-5">
                        <input type="radio" id="status2" name="status" value="0" class="custom-control-input" {{ ($editData[0]->status==0) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="status2">Inactive</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="hidden" name="old_image" value="{{ $editData[0]->image }}">
            <input type="file" name="editimage" class="form-control-file form-control height-auto file-upload-input" onchange="readURL(this);" accept="image/*" />
            <div class="file-upload-content " style="display: {{ ($editData[0]->image == '') ? 'none' : '' }}">
                <img class="file-upload-image" src="{{ url('uploads/schema/'.$editData[0]->image) }}" alt="your image" />
            </div>
        </div>
        <div class="form-group">
            <label>Plan Document</label>
            <input type="hidden" name="old_document" value="{{ $editData[0]->documents }}">
            <input type="file" name="edit_schema_document" class="form-control-file form-control height-auto " />
        </div>
        <div class="form-group ">
            <div class="btn-list">
                <button type="submit" id="btnSubmit" class="btn btn-success">Add</button>
                <a class="btn btn-danger" href="{{ $cancelBtn }}" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>