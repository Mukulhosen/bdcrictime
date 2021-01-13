<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<base href="<?php echo base_url(); ?>">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="icon" href="assets/images/icons/fav_icon.png" type="image/gif" sizes="16x16">
	<title><?php echo isset($meta_title) ? $meta_title : ''; ?></title>

	<meta name="description" content="<?php echo isset($meta_description) ? $meta_description : ''; ?>"/>
	<meta name="keywords" content="<?php echo isset($meta_keywords) ? $meta_keywords : ''; ?>"/>

	<meta property="og:image" content="<?php echo isset($image) ? base_url() . $image : ""; ?>">
	<meta property="og:title" content="<?php echo isset($meta_title) ? $meta_title : getSettingItem('comName'); ?>">
	<meta property="og:description" content="<?php echo isset($meta_description) ? $meta_description : ''; ?>">

	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="@allnewsngr">

	<meta name="twitter:creator" content="<?php echo isset($name) ? $name : '' ?>">
	<meta name="twitter:title" content="<?php echo isset($meta_title) ? $meta_title : getSettingItem('comName'); ?>">
	<meta name="twitter:description" content="<?php echo isset($meta_description) ? $meta_description : ''; ?>">
	<meta name="twitter:image" content="<?php echo isset($image) ? base_url() . $image : ""; ?>">

	<meta name="robots" content="max-image-preview:large">
	<meta name="apple-itunes-app" content="app-id=1520209018">
	<meta name="google-play-app" content="app-id=ng.allnews.allnews">

	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/magnific-popup.css">
	<link rel="stylesheet" href="assets/css/select2.min.css">
	<link rel="stylesheet" href="assets/css/slick.css">
	<link rel="stylesheet" href="assets/css/dropify.min.css">
	<link rel="stylesheet" href="assets/css/dropify.min.css">
	<link rel="stylesheet" href="assets/css/slick-theme.css">
	<link rel="stylesheet" href="assets/css/datatables.min.css">
	<link rel="stylesheet" href="assets/css/jquery.smartbanner.css">
	<link rel="stylesheet" href="assets/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
	<script src="assets/js/jquery-3.5.1.min.js"></script>
	<!-- Facebook Pixel Code -->
	<script>
		!function (f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function () {
				n.callMethod ?
					n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script',
			'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '3073110239476046');
		fbq('track', 'PageView');
	</script>
	<noscript>
		<img height="1" width="1" src="https://www.facebook.com/tr?id=3073110239476046&ev=PageView&noscript=1"/>
	</noscript>
	<!-- End Facebook Pixel Code -->
</head>

<body>

<div class="back-droper"></div>


<header class="header-area d-none d-lg-block">
	<div class="header-top-area">
		<div class="container">
		</div>
	</div>
</header>


<?php load_module_asset('my_account', 'css'); ?>
<div class="account-area">
	<div class="container">
		<div class="row">
			<div class="col-xl-4 offset-xl-4 col-md-6 offset-md-3 col-12">
				<div id="respond"></div>
				<form  id="credential" action="<?php echo base_url('auth/admin'); ?>" method="post" class="account-wrap login-info-wrap">
					<h3 class="account-title">ADMIN SIGN IN</h3>
					<input type="email" name="username" autocomplete="off" class="input-form"  placeholder="Email">
					<input type="password" name="password" autocomplete="off" class="input-form" placeholder="Password">
					<ul class="remember-list">
						<li>
							<input name="remember" type="checkbox" class="checkobx" id="rememberMe">
							<label class="checkbox-style" for="rememberMe">Remember Me</label>
						</li>
<!--						<li class="g-recaptcha mb-2 mt-2" data-sitekey="--><?php //echo config_item('site_key'); ?><!--"></li>-->
						<li>
							<button type="submit" id="admin_signin" class="account-btn">Sign in</button>
						</li>
					</ul>
<!--					<p>Forgotten your password ? <a href="auth/forget-password">Recover Password</a></p>-->
				</form>
			</div>
		</div>
	</div>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script>
<!--    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>-->
<!--    <script src="assets/js/jquery.marquee.min.js"></script>-->
<?php load_module_asset('my_account', 'js'); ?>



<footer class="footer-area">
	<div class="container">

	</div>
</footer>

<button class="scrollTopBtn"><i class="fa fa-angle-up"></i></button>

<!-- <script src="assets/js/jquery-3.5.1.min.js?<?php echo time(); ?>"></script> -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/select2.min.js"></script>
<script src="assets/js/slick.min.js"></script>
<script src="assets/js/jquery.scrollbar.min.js"></script>
<script src="assets/js/dropify.min.js"></script>
<script src="assets/js/jquery.marquee.min.js"></script>
<script src="assets/js/datatables.min.js"></script>
<script src="assets/js/dataTables.responsive.min.js"></script>
<script src="assets/js/jquery.smartbanner.js"></script>
<script src="assets/js/jquery.pause.js?v=<?php echo time(); ?>"></script>
<script src="assets/js/lazysizes.min.js?v=<?php echo time(); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="assets/js/script.js?v=<?php echo time(); ?>"></script>
<!--<script src='https://www.google.com/recaptcha/api.js' async defer ></script>-->





</body>

</html>
