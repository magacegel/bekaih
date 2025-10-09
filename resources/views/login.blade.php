<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; UT MEASUREMENT BKI</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="assets/modules/select2/dist/css/select2.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="assets/modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
</head>
<style type="text/css">
  body {
    background: url('images/login_background.jpg') no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    background-color: #fff;
  }
  .filter
  {
    background: #FFF;
    width: 100%;
    height: 100%;
    opacity: 0.5;
    position: absolute;
  }
  .container {
    margin-left: 0px !important;
    margin-top: 0px !important;
    margin-right: 0px !important;
    margin-bottom: 0px !important;
    margin:0 auto !important;
  }
</style>
  <div class="filter"></div>
  <body>
  <div id="app">
    <section class="section">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand" style="padding-top:20px;">
              <img src="assets/img/bki-logo.png" alt="logo" width="100" class="">
            </div>

            <div class="card card-primary">
              {{-- Error Alert --}}
              @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{session('error')}}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              @endif

              {{-- Tambahkan ini untuk menampilkan pesan login gagal --}}
              @if($errors->has('login_gagal'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ $errors->first('login_gagal') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              @endif
              <div class="card-header">
              <h4>Login</h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h3>UT-BKI</h3>
              </div>
              <div class="card-body">
                <form method="POST" id="loginForm" action="{{url('proses_login')}}" class="needs-validation" novalidate="">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="username">Username</label>
                      <input
                        class="form-control py-0"
                        id="inputUsername"
                        name="username"
                        type="text"
                        value="{{old('username', '')}}"
                        placeholder="Masukkan Username"
                        required/>
                      @if($errors->has('username'))
                      <span class="error">{{ $errors->first('username') }}</span>
                      @endif
                      <div class="invalid-feedback">
                      Please fill in your email
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="d-block">
                    	<label for="password" class="control-label">Password</label>
                      <div class="float-right">
                        <a href="#" class="text-small">
                          Forgot Password?
                        </a>
                      </div>
                    </div>
                    <input
                      class="form-control py-2"
                      id="inputPassword"
                      type="password"
                      name="password"
                      placeholder="Masukkan Password"/>
                    @if($errors->has('password'))
                    <span class="error">{{ $errors->first('password') }}</span>
                    @endif
                    <div class="invalid-feedback">
                      please fill in your password
                    </div>
                  </div>
                  <div class="form-group" id="recaptcha-container">
                    @if($errors->has('g-recaptcha-response'))
                    <span class="error">{{ $errors->first('g-recaptcha-response') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                      <label class="custom-control-label" for="remember-me">Remember Me</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="text-center">
              Copyright &copy; bki.co.id <?=date('Y');?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="assets/modules/jquery.min.js"></script>
  <script src="assets/modules/popper.js"></script>
  <script src="assets/modules/tooltip.js"></script>
  <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="assets/modules/moment.min.js"></script>
  <script src="assets/js/stisla.js"></script>
  <script src="assets/modules/select2/dist/js/select2.full.min.js"></script>

  <!-- JS Libraies -->
  <script
    src="https://sentry.bki.co.id/js-sdk-loader/712c5cb803364ff27febcc538ce083c4.min.js"
    crossorigin="anonymous"
  ></script>

  <script>
    Sentry.onLoad(function() {
      Sentry.init({
        integrations: [
          Sentry.replayIntegration({
            maskAllText: false,
            blockAllMedia: false,
          }),
        ],
        // Session Replay
        replaysSessionSampleRate: 0.1, // This sets the sample rate at 10%. You may want to change it to 100% while in development and then sample at a lower rate in production.
        replaysOnErrorSampleRate: 1.0, // If you're not already sampling the entire session, change the sample rate to 100% when sampling sessions where errors occur.
      });
    });
  </script>

  <!-- Page Specific JS File -->

  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>

<script>
  $('#loginForm').submit(function(event) {
    @if (config('app.env') === 'production')
      event.preventDefault();
      grecaptcha.ready(function() {
        grecaptcha.execute("{{ env('GOOGLE_RECAPTCHA_KEY') }}", {action: 'login'}).then(function(token) {
          $('input[name="token"]').remove();
          $('#loginForm').append('<input type="hidden" name="token" value="' + token + '">');
          this.submit();
        }.bind(this));
      }.bind(this));
    @endif
    // If not production, form will submit normally without reCAPTCHA
  });
</script>
</body>
</html>
