@include('auth.includes.header', ['title'=>'Forgot Password | Triab'])
<body style="background-color: #25245e" class="container-fluid">
<div class="container">
    <div class="text-center" style="margin-top: 20px;margin-bottom:20px">
        <a href="/" class="logo-link">
            <img class="logo-img logo-img-lg" src="/assets/frontpage/img/logo.png" alt="logo">
        </a>
    </div>
    <div class="row justify-content-center" style="margin:20px">
        <div class="col-lg-4 col-md-6 bg-white p-3" style="border-radius: 10px">
            @if(session('success'))
                <div>
                    <h4 class="text-center text-success">Sent</h4>
                    <p class="text-center text-primary">
                        A password reset link has been sent to your email. Click the link to update your password.
                    </p>
                </div> 
            @else 
                <br>
                <h4 class="text-center">Forgot Password</h4>
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
                <form action="{{route('forgot')}}" method="POST" class="form-validate is-alter" autocomplete="off">
                    <div class="form-group">
                        <div class="form-label-group">
                            <label class="form-label">Email address</label>
                        </div>
                        <div class="form-control-wrap"><input autocomplete="off" value='{{old('email')}}' type="email" name="email" class="form-control form-control-lg" required placeholder="Enter your email address"></div>
                    </div> 
                    @csrf
                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-block">Get Reset Link</button>
                    </div>
                    <div class="text-center">
                        <a href="{{route('login')}}">Login</a>
                    </div>
                </form>
            @endif 
        </div>
    </div>
</div>

</body> 
</html>