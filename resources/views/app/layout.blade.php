<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dulce App</title>
    <link rel="stylesheet" href="/assets/css/dashlite.css" />
    <link id="skin-default" rel="stylesheet" href="/assets/css/theme.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="/logo.png">
    <script>
        function loadButton(btn){
            $(btn).html("<i class='fa-solid fa-arrows-spin fa-spin'></i>");
            $(btn).attr('disabled', true);
        }
        function unLoadButton(btn, value){
            $(btn).html(value);
            $(btn).attr('disabled', false);
        }
    </script>
    <style>
        #msg .alert{
            padding:5px; 
        }
        .msg .alert{
            padding:5px;
        }
        #msg, .msg{
            margin-bottom:5px;
        }
        .nk-content {
            padding-top:5px;
        }
        .table-nowrap td, .table-nowrap th {
            white-space: nowrap;
        }
    </style>
</head>
    <body class="nk-body bg-lighter npc-general has-sidebar">
        <div class="nk-app-root">
            <div class="nk-main">
                @include('app.includes.sidebar')
                <div class="nk-wrap">
                    @include('app.includes.header')
                    <div class="nk-content">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-footer">
                        <div class="container-fluid">
                            <div class="nk-footer-wrap">
                                <div class="nk-footer-copyright">&copy; 2024 Tech Breakers LTD.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/bundle.js"></script>
        <script src="/assets/js/scripts.js"></script>
        <script src="/assets/js/demo-settings.js"></script>
        <script src="/assets/js/charts/chart-crm.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
        @if(session('success'))
            <script>
                Swal.fire(
                '{{session('success')}}',
                '',
                'success'
                )
            </script>
        @elseif(session('error'))
            <script>
                Swal.fire(
                '{{session('error')}}',
                '',
                'error'
                )
            </script>
        @endif 
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                }
            });
        </script>
    </body>
</html>