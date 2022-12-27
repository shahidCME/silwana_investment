<style>
    .report-wrapper{
        padding: 0px 20px;
    }
    .dt-buttons{
        margin-bottom: -20px;
    }
    .dt-buttons .dt-button{
        padding: 3px 10px !important;
        background: #CCA44C !important;
        border: 2px solid black !important;
        border-radius: 5px !important;
    }
    .dataTables_length{
        position: relative;
        top: 46px;
    }

    
    /* ----------------- */
    /* ----------------- */
    
    @media screen and (max-width: 767px){
        .dataTables_length {
            position: relative;
            top: 46px !important;
            left: -253px !important;
     }
     .dataTables_filter{
        margin-left: 371px !important;
     }
     .dt-buttons {
        margin-bottom: -20px;
        padding: 0px 7px;
    }
    } 

    /* ----------------- */
    /* ----------------- */
    @media screen and (max-width: 700px) {
        .dataTables_length {
        position: relative;
        top: 46px !important;
        left: -226px !important;
     }
     .dataTables_filter{
        margin-left: 310px !important;
     }
    .dt-buttons {
        margin-bottom: -20px;
        padding: 0px 7px;
    }
    }

    /* ----------------- */
    /* ----------------- */
    @media screen and (max-width: 680px){
        dataTables_length {
        position: relative;
        top: 46px !important;
        left: -184px !important;
    }
    .dataTables_length {
        position: relative;
        top: 46px !important;
        left: -213px !important;
    }
    .dataTables_filter {
        margin-left: 292px !important;
    }
     
    }

    /* ----------------- */
    /* ----------------- */
    @media screen and (max-width: 680px){
        .dataTables_length {
            position: relative;
            top: 46px !important;
            left: -173px !important;
        }
        .dataTables_filter {
          margin-left: 219px !important;
        }
        .dataTables_length {
            position: relative;
            top: 48px !important;
            left: -173px !important;
        }
    } 

    /* ----------------- */
    /* ----------------- */
    @media screen and (max-width: 576px) {
        .dataTables_filter {
            margin-left: 198px !important;
        }
    }

</style>


<div class="card-box  investment-card-box  mb-30 ">
    <div class="pd-20">
        <h4 class="text-blue h4">{{ $title }} </>
    </div>

    <div class="report-wrapper"> 
    <div class="row">

        
        <div class="col-md-3">
            <div class="form-group">
            <label>Select Client</label>
            <select class="custom-select2 form-control" id="customer" name="customer"  style="width: 100%; height: 38px">
                <option value="">Select Client</option>
                @foreach ($getCustomer as $item)
                <option value="{{ $item->id}}">{{ $item->fname.' '.$item->lname }}</option>
                @endforeach
                </select>
                <label id="customer-error" class="error" for="customer">@error('customer') {{ $message }} @enderror</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Start Date From</label>
                <input class="form-control date-picker" id="start_date" name="start_date" placeholder="Select date" type="text" autocomplete="off">
                <label id="start_date-error" class="error" for="start_date">@error('start_date') {{ $message }}  @enderror</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Start Date To</label>
                <input class="form-control date-picker" id="end_date" name="end_date" placeholder="Select date" type="text" autocomplete="off">
                <label id="end_date-error" class="error" for="date_date">@error('date_date') {{ $message }}  @enderror</label>
            </div>
        </div>
        <div class="col-md-3 align-self-center">
            <div class="form-group ">
                <div class="btn-list pt-2">
                    <button type="submit" id="btnSubmit" class="btn btn-warning" style="background-color: #CCA44C !important;">Find</button>
                </div>
            </div>
        </div>
    </div>
<div>
        
    </div>
    <div class="pb-20 investment-card-table">
        
        <table class="table stripe hover nowrap " id="ReportTable" width="100%">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">Client</th>
                    <th>Sales</th>
                    <th>Investment</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Amount</th>
                    <th>Roi</th>
                    <th>Status</th>
                    <th class="datatable-nosort">Action/Contract/pdf </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-4 col-sm-12 mb-30" >
        <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Contact Cancel 
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="contractCancelForm" action="{{ url('contractCancel');}}", method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Comment</label>
                            <textarea class="form-control" name="contractCancelComment"></textarea>
                        </div>
                        <input type="hidden" name="investment_id" id="investment_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">Add</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
</div>