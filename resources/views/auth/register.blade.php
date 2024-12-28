@include('auth.includes.header', ['title'=>'Sign Up | Triab'])
<body style="background-color: #25245e" class="container-fluid">
<div class="container p-4">
    <div class="text-center" style="margin-top: 20px;margin-bottom:20px">
        <a href="/" class="logo-link">
            <img class="logo-img logo-img-lg" src="/assets/frontpage/img/logo.png" alt="logo">
        </a>
    </div>
    <div class="row justify-content-center" >
        <div class="col-lg-5 col-md-6 bg-white p-3" style="border-radius: 10px">
        @if(session('success'))
            <div>
                <h4 class="text-center text-success">Registration Successful</h4>
                <p class="text-center text-primary">
                    A verification link has been sent to your email. Please click on it to verify your account.
                </p>
            </div> 
        @else 
            <br>
            <h4 class="text-center">Create account</h4>
            <p class="text-center">Let's get you started with your Triab account.</p>
            <br>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>&#9432;  {{$error}}</li>
                        @endforeach 
                    </ul>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    <div><i class="fa fa-info-circle"></i> {{session('error')}}</div>
                </div>
            @endif 
            <form action="{{route('register')}}" method="POST" class="form-validate is-alter" autocomplete="off">
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label">Full name <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-control-wrap"><input autocomplete="off" value='{{old('name')}}' type="text" name="name" class="form-control form-control-lg" required placeholder="Enter your Full name"></div>
                </div> 
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-label-group">
                                <label class="form-label">Email address <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-control-wrap"><input autocomplete="off" value='{{old('email')}}' type="email" name="email" class="form-control form-control-lg" required placeholder="Enter email address"></div>
                            <div class="d-block d-md-none"><br></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-group">
                                <label class="form-label">Mobile number <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-control-wrap"><input autocomplete="off" value='{{old('mobile_number')}}' step="any" type="number" name="mobile_number" class="form-control form-control-lg" required placeholder="eg: 08027755940"></div>
                        </div>
                    </div>
                </div> 
                @csrf
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-control-wrap">
                        <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" id="togglePassword">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off" style="display:none;"></em>
                        </a>
                        <input autocomplete="new-password" type="password" name="password" class="form-control form-control-lg" required id="password" placeholder="Enter your password">
                    </div>
                </div>
                <div class="form-group">
                    {{-- <div class="row"> --}}
                        {{-- <div class="col-md-6"> --}}
                            <div class="form-label-group">
                                <label class="form-label">Sponsor</label>
                            </div>
                            <div class="form-control-wrap"><input autocomplete="off" value='{{old('sponsor')}}' type="text" name="sponsor" class="form-control form-control-lg" placeholder="Triab ID"></div>        
                            
                            {{-- </div> --}}
                        {{-- <div class="col-md-6">
                            <div class="d-lg-none d-md-none d-sm-block"><br></div>
                            <div class="form-label-group">
                                <label class="form-label">Place under</label>
                            </div>
                            <div class="form-control-wrap"><input autocomplete="off" value='{{old('place_under')}}' type="text" name="place_under" class="form-control form-control-lg" placeholder="Triab ID"></div>
                        </div> --}}
                    {{-- </div> --}}
                </div> 
                <div class="form-group">
                    <label for="">
                        <input type="checkbox" value="0" name="terms" style="height: 14px; width:14px"  required>
                        <span style="margin-top:-20px;">Accept Our Terms & Condition.</span>
                    </label>
                    <a href="/terms" target="_blank"> &nbsp; Read</a>
                </div>

                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block">Sign up</button>
                </div>
                <div class="text-center">
                    Already have an account? <a href="/login">Sign in</a>
                </div>
            </form>
        @endif 
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