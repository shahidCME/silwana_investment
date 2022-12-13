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
			href="{{ asset('assets/admin/') }}/vendors/images/favicon_new.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="{{ asset('assets/admin/') }}/vendors/images/favicon_new.png"
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
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
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
						<img src="{{ asset('assets/admin/') }}/vendors/images/logo2.png" alt="" />
					</a>
				</div>
				{{-- <div class="login-menu">
					<ul>
						<li><a href="register.html">Register</a></li>
					</ul>
				</div> --}}
			</div>
		</div>
		<div
			class="login-wrap d-flex align-items-center flex-wrap justify-content-center"
		>
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-6 col-lg-7">
						<img src="{{ asset('assets/admin/') }}/vendors/images/login-img1.png" alt="" />
					</div>
					<div class="col-md-6 col-lg-5">
						<div class="login-box bg-white box-shadow border-radius-10">
							<div class="login-title">
								<h2 class="text-center text-primary">Login</h2>
							</div>
							<form id="loginForm" action="{{$action}}" method="post">
							@csrf
								<div class="input-group custom">
									<input
										type="text"
										class="form-control form-control-lg"
										placeholder="Username"
                                        name="email"
									/>
									<div class="input-group-append custom">
										<span class="input-group-text"
											><i class="icon-copy dw dw-user1"></i
										></span>
									</div>
								</div>
								<label id="email-error" class="error" for="email"></label>
								<div class="input-group custom">
									<input
                                    name="password"
										type="password"
										class="form-control form-control-lg"
										placeholder="**********"
									/>
									<div class="input-group-append custom">
										<span class="input-group-text"
											><i class="dw dw-padlock1"></i
										></span>
									</div>
								</div>
								<label id="password-error" class="error" for="password"></label>
								<div class="row pb-30">
									{{-- <div class="col-6">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="customCheck1"/>
											<label class="custom-control-label" for="customCheck1">Remember</label
											>
										</div>
									</div> --}}
									<div class="col-12">
										<div class="forgot-password">
											<a href="{{ url('/forgetPassword')}}">Forgot Password</a>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="input-group mb-0">
											<!--
											use code for form submit
											<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
										-->
											<input type="submit"
												class="btn btn-primary btn-lg btn-block"
												value="Sign In"
												>
										</div>
										<div
											class="font-16 weight-600 pt-10 pb-10 text-center"
											data-color="#707373"
										>
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
