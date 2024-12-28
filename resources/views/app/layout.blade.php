<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triab | Portal</title>
    <link rel="stylesheet" href="/assets/css/dashlite.css" />
    <link id="skin-default" rel="stylesheet" href="/assets/css/theme.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" href="/assets/frontpage/img/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

@php
    $page_url = url()->current();
@endphp

@if( Str::contains($page_url, 'add-product') || Str::contains($page_url, 'e-shop/edit-product') )
    <script src="https://cdn.tiny.cloud/1/1gn0mphq7v6nblk4a2g5wolaokaod8bkor2vv3m3vt2zdlaj/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@endif 

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
                                <div class="nk-footer-copyright">&copy; {{date('Y')}} Triab Global Resources.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/bundle.js"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
        <script src="/assets/js/scripts.js"></script>
        {{-- <script src="/assets/js/demo-settings.js"></script> --}}
        {{-- <script src="/assets/js/charts/chart-crm.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
        <script src="/notify.js"></script>
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


        @if( Str::contains($page_url, 'add-product') || Str::contains($page_url, 'e-shop/edit-product') )
            <script>
                tinymce.init({
                    selector: '.desc',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists  searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                });
            </script>
        @endif 
        <form action="{{route('logout')}}" method="POST" id="logout_form">
            @csrf 
        </form>
    </body>
</html>