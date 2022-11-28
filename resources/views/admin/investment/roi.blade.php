<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">{{ $title }}</>
        </div>
    <div class="pb-20">
        <table class="table stripe hover nowrap " id="datatable" width="100%">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">Sno.</th>
                    <th>Date of Return</th>
                    <th>Return Amount</th>
                    <th>Status</th>
                    <th class="datatable-nosort">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roi as $i =>$item)
                <tr>
                    <td class="table-plus datatable-nosort">{{ $i+1 }}</td>
                    <td>{{ date('d F Y',strtotime($item->date_of_return) )}}</td>
                    <td>{{ $item->return_amount }}</td>
                    <td><?=($item->status == '0') ? '<button type="button" class="btn btn-warning btn-sm">Pending</button>' : '<button type="button" class="btn btn-success btn-sm">Paid</button>' ?></td>
                    <td class="datatable-nosort">
                        <?php 
                            $date_of_return_month = date('m-Y',strtotime($item->date_of_return));
                            $current_month = date('m-Y');
                            // $attr = ($date_of_return_month != $current_month) ? 'disabled' : '';
                            $downloadBtn = ($item->payment_trasfer_reciept == NULL) ? 'd-none' : '';
                        ?>
                        <?php $btn = '<button data-id="'.encrypt($item->id).'" class="btn btn-primary btn-sm payBtn" data-toggle="modal" data-target="#Medium-modal" type="button" >Pay</button>'; ?>
                        <?=($item->status == '0') ? $btn : '<button type="button" class="btn btn-dark btn-sm">Paid</button>' ?>
                        <?php $btn = '<a target="_blank"  href="'.url('uploads/payment_trasfer_reciept/'.$item->payment_trasfer_reciept).'" class="btn btn-success btn-sm '.$downloadBtn.'" ><i class="fa fa-eye"></i></button>'; ?>
                        <?=$btn?>
                    </td>
                </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-4 col-sm-12 mb-30" >
    {{-- <div class="pd-20 card-box height-100-p">
    </div> --}}
        <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Payment Reciept
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="Roiform" action="", method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Upload File</label>
                            <input type="file" name="payment_trasfer_reciept" class="form-control-file form-control height-auto">
                        </div>
                        <input type="hidden" name="roi_id" id="roi_id" value="">
                        <input type="hidden" name="investment_id" id="investment_id" value="{{ $investment_id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
</div>