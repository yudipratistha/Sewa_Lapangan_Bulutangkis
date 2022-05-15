@extends('layouts.app')

@section('title', 'Login')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
@endsection

@section('content')
<!-- page-wrapper Start-->
<section>         
  <div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
          <div class="login-card">
            <form class="theme-form login-form" method="POST" action="{{ route('login') }}">
            @csrf  
            <h4>Login</h4>
              <h6>Welcome back! Log in to your account.</h6>
              <div class="form-group">
                <label>Email Address</label>
                <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                  <input class="form-control @error('email') is-invalid @enderror" type="email" required="" name="email" placeholder="Test@gmail.com">
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror  
              </div>
              </div>
              <div class="form-group">
                <label>Password</label>
                <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                  <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required="" placeholder="*********">
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                  <div class="show-hide"><span class="show">                         </span></div>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox">
                  <input id="checkbox1" type="checkbox">
                  <label for="checkbox1">Remember password</label>
                </div><a class="link" href="{{ route('password.request') }}">Forgot password?</a>
              </div>
              <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Sign in</button>
              </div>
              @if (Route::has('password.request'))
                  <p>Don't have account?<a class="ms-2" href="{{route('register')}}">Create Account</a></p>
              @endif
              <!-- <div class="login-social-title">                
                <h5>Don't have account?</h5>
              </div>
              <div class="form-group">
                <ul class="login-social">
                  <li><a href="{{route('penyewaLapangan.register')}}"><button class="btn btn-square btn-secondary btn-xs" style="font-size: 10px;">Register Penyewa<br>Lapangan</button></a></li>
                  <li><a href="{{route('pemilikLapangan.register')}}"><button class="btn btn-square btn-secondary btn-xs" style="font-size: 10px;">Register Pemilik<br>Lapangan</button></a></li>
                </ul>
              </div> -->
            </form>
          </div>
        </div>
      </div>  
    </div>
  </div>
</section>
    <!-- page-wrapper end-->
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>

<script>
  function register(){
    swal.fire({
        title: "Register",
        icon: 'warning',
        showConfirmButton: false,
        // showCancelButton: true,
        // confirmButtonText: "Save",
        // showLoaderOnConfirm: true,
        html: `
        <p>sdsdassdsd</p>
        <div style="display: flex;z-index: 1;box-sizing: border-box;flex-wrap: wrap;align-items: center;justify-content: center;width: auto;margin: 1.25em auto 0;padding: 0; line-height: 1.2;">
          <button class="btn btn-primary btn-sm swal2-styled" id="register-penyewastyle="border: 0;border-radius: .25em;background: initial;background-color: initial;background-color: #7066e0;color: #fff;font-size: 1em; line-height: 1.2;">TEst</button>
          <button class="btn btn-danger btn-sm swal2-styled" style="border: 0;border-radius: .25em;background: initial;background-color: initial;background-color: #7066e0;color: #fff;font-size: 1em; line-height: 1.2;">TEst</button>
          <button class="swal2-cancel swal2-styled" >Cancel</button>
        </div>`
    }).then((result) => {
    console.log("sadsa ", result.value)
        if(result.value){
        // swal.fire({title:"New Ticket Data Added", text:"Successfuly add new Ticket data!", icon:"success"})
        // .then(function(){ 
        //     // window.location.reload();
        // });
        }
    })
  }
</script>
@endsection
