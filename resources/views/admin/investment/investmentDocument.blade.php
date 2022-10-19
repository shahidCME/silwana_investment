
<div class="col-md-8 col-sm-12 mb-30" style="margin: 0 auto;">
    <div class="pd-20 card-box ">
        <h4 class="mb-30 h4 text-center"> Investment Details</h4>
        <dl class="row">
            <dt class="col-sm-3">Customer First Name</dt>
            <dd class="col-sm-9">
                {{ $viewData[0]->customerFname }}
            </dd>
            <dt class="col-sm-3">Customer Last Name</dt>
            <dd class="col-sm-9">
                {{ $viewData[0]->customerLname }}
            </dd>
            <dt class="col-sm-3">Sales person First Name</dt>
            <dd class="col-sm-9">
                  {{ $viewData[0]->fname  }}
            </dd>
            <dt class="col-sm-3">Sales person Last Name</dt>
            <dd class="col-sm-9">
                {{ $viewData[0]->lname  }}
            </dd>
            
            <dt class="col-sm-3">Schema Name</dt>
            <dd class="col-sm-9">
                {{ $viewData[0]->schema  }}
            </dd>
            <dt class="col-sm-3">Schema details</dt>
            <dd class="col-sm-9">
                <dl class="row">
                    {{-- <dt class="col-sm-4">Nested definition list</dt> --}}
                    <dd class="col-sm-8">
                        {{ $viewData[0]->details }}
                    </dd>
                </dl>
            </dd>
            
            <dt class="col-sm-3 text-truncate">Start Date</dt>
            <dd class="col-sm-9">
              {{ date('d F Y',strtotime($viewData[0]->start_date))}}
            </dd>

            <dt class="col-sm-3 text-truncate">Return Percentage(%)</dt>
            <dd class="col-sm-9">
              {{ $viewData[0]->return_percentage }}
            </dd>

            <dt class="col-sm-3">Amount</dt>
            <dd class="col-sm-9">
                <dl class="row">
                    {{-- <dt class="col-sm-4">Nested definition list</dt> --}}
                    <dd class="col-sm-8">
                        {{ $viewData[0]->amount  }}
                    </dd>
                </dl>
            </dd>
            <dt class="col-sm-3">Return Type</dt>
            <dd class="col-sm-9">
                <dl class="row">
                    {{-- <dt class="col-sm-4">Nested definition list</dt> --}}
                    <dd class="col-sm-8">
                        {{ ($viewData[0]->return_type =='0') ? 'Monthly' : 'Yearly'  }}
                    </dd>
                </dl>
            </dd>
            <dt class="col-sm-3"> Contract Reciept</dt>
            <dd class="col-sm-9">
                <dl class="row">
                    {{-- <dt class="col-sm-4">Nested definition list</dt> --}}
                    <dd class="col-sm-8">
                        <a href="{{ url('uploads/contract_reciept/'.$viewData[0]->contract_reciept) }}" class="btn btn-dark btn-lg" download>
                            <i class="fa fa-download"></i>
                            Download
                        </a>
                    </dd>
                </dl>
            </dd>
            <dt class="col-sm-3"> Investment document</dt>
            <dd class="col-sm-9">
                <dl class="row">
                    {{-- <dt class="col-sm-4">Nested definition list</dt> --}}
                    <dd class="col-sm-8">
                        <a href="{{ url('uploads/invest_document/'.$viewData[0]->investment_doc) }}" class="btn btn-dark btn-lg" download>
                            <i class="fa fa-download"></i>
                            Download
                        </a>
                    </dd>
                </dl>
            </dd>
            @if($viewData[0]->other_doc != '')
            <dt class="col-sm-3"> Other document</dt>
            <dd class="col-sm-9">
                <dl class="row">
                    {{-- <dt class="col-sm-4">Nested definition list</dt> --}}
                    <dd class="col-sm-8">
                        <a href="{{ url('uploads/invest_document/'.$viewData[0]->other_doc) }}" class="btn btn-dark btn-lg" download>
                            <i class="fa fa-download"></i>
                            Download
                        </a>
                    </dd>
                </dl>
            </dd>
            @endif
            
        </dl>
    </div>
</div>