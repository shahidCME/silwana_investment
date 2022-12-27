<!DOCTYPE html>
<html>
	<head>
    @include('admin.includes.header')
        </head>
<body style="position: relative">
			<input type="hidden" id="base_url" value="{{url('/')}}/" />
            <div class="header">
            @include('admin.includes.navbar')
        <!-- navbar -->
    </div>
    
    
    
    <div class="left-side-bar">
    @include('admin.includes.sidebar')
    
    <!-- sidebar -->
</div>
<div class="mobile-menu-overlay"></div>


<div class="main-container">
<div class="xs-pd-20-10 pd-ltr-20">
@yield('content')
			  <!-- footer -->
              @include('admin.includes.footer')
			</div>
		</div>
		
		<!-- welcome modal end -->
		<!-- js -->
		
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/core.js"></script>
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/script.min.js"></script>
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/process.js"></script>
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/layout-settings.js"></script>
		<script src="{{ asset('assets/admin/') }}/src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="{{ asset('assets/admin/') }}/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="{{ asset('assets/admin/') }}/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="{{ asset('assets/admin/') }}/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="{{ asset('assets/admin/') }}/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/dashboard3.js"></script>	
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/common.js"></script>
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/dashboard.js"></script>	
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js" ></script>
		<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" type="text/javascript"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
    	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js" type="text/javascript"></script>
    	<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js" type="text/javascript"></script>
    	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js" type="text/javascript"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
		@if(isset($js))
		@foreach($js as $value)
		<script src="{{ asset('assets/admin/') }}/js/{{$value}}.js"></script>
		@endforeach
		@endif
		<script src="{{ asset('assets/admin/js/confirm.js')}}"></script>
	</body>
</html>
