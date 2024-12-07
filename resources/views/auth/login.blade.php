<html lang="en" class="wf-publicsans-n3-active wf-publicsans-n4-active wf-publicsans-n5-active wf-publicsans-n6-active wf-publicsans-n7-active wf-fontawesome5solid-n4-active wf-fontawesome5regular-n4-active wf-fontawesome5brands-n4-active wf-simplelineicons-n4-active wf-active"><head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport">
    <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon">

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Public+Sans:300,400,500,600,700" media="all"><link rel="stylesheet" href="assets/css/fonts.min.css" media="all"><script>
		WebFont.load({
			google: {"families":["Public Sans:300,400,500,600,700"]},
			custom: {"families":["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['assets/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/kaiadmin.min.css')}}">
  </head>
  <body class="login bg-primary" data-new-gr-c-s-check-loaded="14.1207.0" data-gr-ext-installed="">
    <div class="wrapper wrapper-login">
      <div class="container container-login animated fadeIn" style="display: block;">

        <h3 class="text-center">Sign In</h3>
   <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="login-form">
          <div class="form-sub">
            <div class="form-floating form-floating-custom mb-3">
              <input id="username"  class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">
              <label for="username">Username</label>
            </div>
            <div class="form-floating form-floating-custom mb-3">
              <input id="password" name="password" type="password" class="form-control" type="password"  name="password" required autocomplete="current-password" >
              <label for="password">Password</label>
              <div class="show-password">
                <i class="icon-eye"></i>
              </div>
            </div>
          </div>
          <div class="row m-0">
            <div class="d-flex form-sub">
           

            </div>
          </div>
          <div class="form-action mb-3">
            <button class="btn btn-primary w-100 btn-login" type="submit">Sign In</button>
          </div>
          <div class="login-account">
          </div>
        </div>
    </form>
      </div>

      
    </div>
    <script src="{{asset('assets/js/core/jquery-3.7.1.min.js')}}"></script>
    
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/kaiadmin.min.js')}}"></script>
  

</body>
</html>