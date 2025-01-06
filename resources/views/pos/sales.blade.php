<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triab-{{$shop->name}}</title>
    <link rel="shortcut icon" href="{{Storage::url($shop->logo)}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/pos/css/app.css')
    @viteReactRefresh
    @vite('resources/pos/js/app.jsx')
    <script>
        window.products = @json($products);
        window.shop_id = '{{$shop->id}}';
        window.service_fee = '{{$shop->service_fee}}'; 
        window.vat = '{{$shop->vat}}';
    </script>
</head>
<body>
<div id="app"></div>
</body>
</html>
