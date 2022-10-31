<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">{{ $title }}</>
        </div>
    <div class="pb-20">
        <table class="table stripe hover nowrap " id="data-table">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">Sno.</th>
                    <th>Date of Return</th>
                    <th>Return Amount</th>
                    <th>Return(%)</th>
                    <th class="datatable-nosort">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roi as $i =>$item)
                <tr>
                    <td class="table-plus datatable-nosort">{{ $i+1 }}</td>
                    <td>{{ date('d F Y',strtotime($item->date_of_return) )}}</td>
                    <td>{{ $item->return_amount }}</td>
                    <td>{{ $item->return_percentage }}</td>
                    <td class="datatable-nosort">
                         <?=($item->status == '0') ? '<button type="button" class="badge badge-warning">Pending</button>' : '<button type="button" class="badge badge-success">Paid</button>' ?>
                        <?php 
                            $date_of_return_month = date('m-Y',strtotime($item->date_of_return));
                            $current_month = date('m-Y');
                            $attr = (($date_of_return_month != $current_month) && $item->status == '0' ) ? 'd-none' : '';
                        ?>
                        <?php  $btn = '<a  href="'.url('uploads/payment_trasfer_reciept/'.$item->payment_trasfer_reciept).'" class="badge badge-success '.$attr.'" download><i class="fa fa-download"></i></button>'; ?>
                        <?=$btn?>
                    </td> 
                </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
</div>