@include('auth.includes.header', ['title'=>'POS | Triab'])
<body style="background-color: #25245e" class="container-fluid">
<div class="container p-4">
    <div class="text-center" style="margin-top: 20px;margin-bottom:20px">
        <a href="/" class="logo-link">
            <img class="logo-img logo-img-lg" src="/assets/frontpage/img/logo.png" alt="logo">
        </a>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 bg-white p-3" style="border-radius: 10px">
            <br>
            <h4 class="text-center">Triab POS</h4>
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
                    <div><i class="fa fa-info-circle"></i> {{session('error')}}</div>
                </div>
            @elseif(session('success'))
                <div class="alert alert-success">
                    <div>
                        <i class="fa fa-check-circle"></i> {{session('success')}}
                    </div>
                </div>
            @endif 
            <form method="POST" id="shop_pos_form" class="form-validate is-alter" autocomplete="off">
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label">Shop ID</label>
                    </div>
                    <div class="form-control-wrap"><input id="shop_input" autocomplete="off" value='{{old('shop_id')}}' type="text" name="shop_id" class="form-control form-control-lg" required placeholder="Enter Shop ID"></div>
                </div> 
                @csrf
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="password">Your Pass code</label>
                    </div>
                    <div class="form-control-wrap">
                        <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" id="togglePassword">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off" style="display:none;"></em>
                        </a>
                        <input autocomplete="new-password" type="password" name="pass_code" class="form-control form-control-lg" required id="password" placeholder="pos pass code">
                    </div>
                </div>
                <div class="msg"></div> 
                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block">Sign In</button>
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

var old_shop = localStorage.getItem('old_shop');
if(old_shop){
    document.getElementById('shop_input').value = old_shop; 
}

document.addEventListener('submit', function (e) {
    if (e.target && e.target.id === 'shop_pos_form') {
        e.preventDefault();

        const form = e.target;
        const msg = form.querySelector('.msg');
        const btn = form.querySelector('button');
        const btnContent = btn.textContent;

        msg.innerHTML = ''; // Clear previous messages
        loadButton(btn);

        const formData = new FormData(form); // Create FormData object

        fetch('{{route('pos.logger')}}', {
            method: 'POST',
            body: formData,
        })
        .then(async (response) => {
            unLoadButton(btn, btnContent);

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const res = await response.json();
            if (res.error) {
                msg.innerHTML = `<p class="alert alert-danger"><i class="fa-solid fa-circle-info"></i> ${res.error}</p>`;
            } else if (res.token) {
                localStorage.setItem('authToken', res.token);
                localStorage.setItem('old_shop', res.shop_id); 
                localStorage.setItem('admin', JSON.stringify(res.admin));
                msg.innerHTML = `<p class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Successful</p>`;
                window.location.href = `/pos/${res.id}`;
            }
        })
        .catch((error) => {
            console.error(error);
            unLoadButton(btn, btnContent);

            if (!navigator.onLine) {
                msg.innerHTML = "<div class='alert alert-danger'><i class='fa-solid fa-circle-info'></i> Network error: Please check your internet connection.</div>";
            } else {
                msg.innerHTML = "<div class='alert alert-danger'><i class='fa-solid fa-circle-info'></i> Something went wrong, please try again</div>";
            }
        });
    }
});


// Helper functions
function loadButton(button) {
    button.textContent = 'Checking...'; // Modify this to suit your loading behavior
    button.disabled = true;
}

function unLoadButton(button, originalContent) {
    button.textContent = originalContent; // Restore original button text
    button.disabled = false;
}

</script>
</body> 
</html>