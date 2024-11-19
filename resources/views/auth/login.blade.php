<!DOCTYPE html>
<html lang="en" class="js">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <link rel="shortcut icon" href="/assets/frontpage/img/logo.png">
    <title>Login | Triab</title>
    <link rel="stylesheet" href="/assets/css/dashlite.css">
    <link id="skin-default" rel="stylesheet" href="/assets/css/theme.css">
</head>
<body style="background-color: #25245e" class="container-fluid">
<div class="container">
    <div class="text-center" style="margin-top: 20px;margin-bottom:20px">
        <a href="/" class="logo-link">
            <img class="logo-img logo-img-lg" src="/assets/frontpage/img/logo.png" alt="logo">
        </a>
    </div>
    <div class="row justify-content-center" style="margin:20px">
        <div class="col-lg-4 col-md-6 bg-white p-3" style="border-radius: 10px">
            <br>
            <h4 class="text-center">Sign in to your account</h4>
            <br>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach 
                    </ul>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    <div>&#9432; {{session('error')}}</div>
                </div>
            @endif 
            <form action="{{route('login')}}" method="POST" class="form-validate is-alter" autocomplete="off">
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label">Email address</label>
                    </div>
                    <div class="form-control-wrap"><input autocomplete="off" value='{{old('email')}}' type="email" name="email" class="form-control form-control-lg" required placeholder="Enter your email address"></div>
                </div> 
                @csrf
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="password">Password</label>
                    </div>
                    <div class="form-control-wrap">
                        <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" id="togglePassword">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off" style="display:none;"></em>
                        </a>
                        <input autocomplete="new-password" type="password" name="password" class="form-control form-control-lg" required id="password" placeholder="Enter your password">
                    </div>
                </div>
                <div style="margin-bottom: 5px">
                    <a href="">Forgot password</a>
                </div>
                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block">Sign In</button>
                </div>
                <div class="text-center">
                    New to Triab? <a href="{{route('register')}}">Create an account</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('togglePassword').addEventListener('click', function (e) {
    e.preventDefault();
    const passwordInput = document.getElementById('password');
    const showIcon = this.querySelector('.icon-show');
    const hideIcon = this.querySelector('.icon-hide');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        showIcon.style.display = 'none';
        hideIcon.style.display = 'inline';
    } else {
        passwordInput.type = 'password';
        showIcon.style.display = 'inline';
        hideIcon.style.display = 'none';
    }
});
</script>
</body> 
</html>