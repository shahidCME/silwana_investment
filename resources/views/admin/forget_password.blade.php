<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Admin Login</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="{{ asset('assets/admin/') }}/vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="{{ asset('assets/admin/') }}/vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="{{ asset('assets/admin/') }}/vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="{{ asset('assets/admin/') }}/vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/vendors/styles/style.css" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85" ></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->
	</head>
	<body class="login-page">
		<?php 
		if(Session::has('Mymessage')){ 
			echo  Session::get('Mymessage');
		} 
		?>
		<div class="login-header box-shadow">
			<div
				class="container-fluid d-flex justify-content-between align-items-center"
			>
				<div class="brand-logo">
					<a href="{{ url('/')}}">
						<img src="{{ asset('assets/admin/') }}/vendors/images/logo2.svg" alt="" />
					</a>
				</div>
				{{-- <div class="login-menu">
					<ul>
						<li><a href="#">Login</a></li>
					</ul>
				</div> --}}
			</div>
		</div>
		<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-6">
						<img src="{{ asset('assets/admin/') }}/vendors/images/forgot_pass.png" alt="" />
					</div>
					<div class="col-md-6">
						<div class="login-box bg-white box-shadow border-radius-10">
							<div class="login-title">
								<h2 class="text-center text-primary">Forgot Password</h2>
							</div>
							<h6 class="mb-20">
								Enter your email address to reset your password
							</h6>
							<form id="forgetPassword" action="{{ $action }}" method="POST">
                                @csrf
								<div class="input-group custom">
									<input type="text" name="email" class="form-control form-control-lg" placeholder="Email"/>
									<div class="input-group-append custom">
										<span class="input-group-text"
											><i class="fa fa-envelope-o" aria-hidden="true"></i
										></span>
									</div>
								</div>
                                <label id="email-error" class="error" for="email">@error('email') {{ $message }}  @enderror</label>
								<div class="row align-items-center">
									<div class="col-5">
										<div class="input-group mb-0">
											{{-- use code for form submit --}}
											<input  class="btn btn-primary btn-lg btn-block" type="submit" id="btnSubmit" value="Submit">
											{{-- <a
												class="btn btn-primary btn-lg btn-block"
												href="index.html"
												>Submit</a
											> --}}
										</div>
									</div>
									<div class="col-2">
										<div class="font-16 weight-600 text-center" data-color="#707373" >
											OR
										</div>
									</div>
									<div class="col-5">
										<div class="input-group mb-0">
											<a class="btn btn-outline-primary btn-lg btn-block" href="{{ url('/')}}">Login</a
											>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- welcome modal start -->
	
		<!-- welcome modal end -->
		<!-- js -->
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/core.js"></script>
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/script.min.js"></script>
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/process.js"></script>
		<script src="{{ asset('assets/admin/') }}/vendors/scripts/layout-settings.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js" ></script>
		@if(isset($js))
			@foreach($js as $value)
				<script src="{{ asset('assets/admin/') }}/js/{{$value}}.js"></script>
			@endforeach
		@endif
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
