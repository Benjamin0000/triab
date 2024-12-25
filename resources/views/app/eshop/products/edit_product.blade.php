@extends('app.layout')
@section('content')
<style>
    .delete-image {
        top: 5px; 
        right: 5px; 
        padding: 2px 5px; 
        border-radius: 50%;
        font-weight: bold; 
        line-height: 1; 
        cursor: pointer; 
        font-size: 30px;
    }
</style>
<div class="container-fluid">
    <div class="card card-bordered h-100">
        <div class="card-inner border-bottom">
            <div class="header-section">
                <p><a href="{{route('eshop.products', [$product->shop->id, $product->parent_id])}}" class="btn btn-sm btn-primary">
                    <i class="fa fa-arrow-left"></i> &nbsp;Go Back</a>
                </p>
                <br>
                <h4>{{$product->shop->name}} | Products</h4>
                <div>{{$product->shop->storeID}}</div>
            </div>
            
            @foreach ($titles as $title)
                <span> Category <i class="fa fa-arrow-right"></i> {{$title}}</span>
            @endforeach
            <br>
            <br>
            <h5>Edit Product</h5>

            <form id="add_item_form" method="POST" action="{{route('eshop.product.update', $product->id)}}" enctype="multipart/form-data">    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Product Name</label>
                            <div class="form-control-wrap">
                                <input name="name" type="text" class="form-control" value="{{$product->name}}" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-block d-md-none"><br></div>
                        <div class="form-group">
                            <label class="form-label">Cost Price</label>
                            <div>
                                <input type="number" class="form-control" name="cost_price" required step="any" value="{{$product->cost_price}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-block d-md-none"><br></div>
                        <div class="form-group">
                            <label class="form-label">Selling Price</label>
                            <div>
                                <input type="number" class="form-control" name="selling_price" required step="any" value="{{$product->selling_price}}">
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="form-group">
                    <label class="form-label" for="">Product Details </label>
                    <textarea name="description" class="form-control desc" placeholder="Share details about this product with your customers.">{!!$product->description!!}</textarea>
                </div>

                <div class="form-group">
                    <div><b>Product Images</b></div>
                    <small class="text-muted">Select single or multiple images (Max size: 100KB per image).</small>
                    <br>
                    <div class="mb-2">
                        <a type="button" href="javascript:void(0)" id="addImageButton" class="btn btn-primary btn-sm">Add Image</a>
                        <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="visibility: hidden">
                    </div>
                    <div id="imagePreview" class="d-flex flex-wrap">
                        @foreach($product->images as $image)
                            <div class="position-relative mr-2 mb-2 existing-image-container" data-src="{{ $image }}">
                                <img src="{{ Storage::url($image) }}"
                                    class="img-thumbnail"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <a href="javascript:void(0)"
                                        class="btn btn-danger btn-sm position-absolute remove-image delete-image">
                                    ×
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @csrf
                <input type="hidden" name="shop_id" value="{{$product->shop_id}}">

                <div class="msg"></div>
                <div class="form-group">
                    <button class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const maxFileSize = 100 * 1024; // 100KB
        const imageInput = document.getElementById('imageInput');
        const addImageButton = document.getElementById('addImageButton');
        const imagePreview = document.getElementById('imagePreview');
        const form = document.getElementById('add_item_form');
        const msg = form.querySelector('.msg');
        const btn = form.querySelector('button');
        const selectedFiles = [];
    
        // Load existing images into selectedFiles on page load
        document.querySelectorAll('.existing-image-container').forEach(container => {
            const src = container.getAttribute('data-src');
            const removeBtn = container.querySelector('.remove-image');
            selectedFiles.push({ file: null, path: src });
    
            // Handle remove button click for existing images
            removeBtn.addEventListener('click', () => {
                const index = selectedFiles.findIndex(item => item.path === src);
                if (index !== -1) selectedFiles.splice(index, 1); // Remove from selectedFiles array
                container.remove(); // Remove the container from the DOM
            });
        });
    
        addImageButton.addEventListener('click', () => imageInput.click());
    
        imageInput.addEventListener('change', (event) => {
            const files = Array.from(event.target.files);
    
            files.forEach((file) => {
                if (file.size > maxFileSize) {
                    alert(`${file.name} exceeds the maximum size of 100KB.`);
                    return;
                }
    
                selectedFiles.push({ file });
    
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('position-relative', 'mr-2', 'mb-2');
                    imgContainer.style.display = 'inline-block';
    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
    
                    const removeBtn = document.createElement('a');
                    $(removeBtn).attr('href', 'javascript:void(0)')
                    removeBtn.textContent = '×';
                    removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'delete-image');
                    removeBtn.addEventListener('click', () => {
                        const index = selectedFiles.findIndex(item => item.file === file);
                        if (index !== -1) selectedFiles.splice(index, 1); // Remove file from selectedFiles array
                        imgContainer.remove(); // Remove the image preview from the DOM
                    });
    
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    imagePreview.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            });
    
            imageInput.value = '';
        });
    
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const btnContent = btn.textContent;
            msg.innerHTML = '';
            loadButton(btn);
    
            const formData = new FormData(form);
            selectedFiles.forEach((fileData, index) => {
                if (fileData.file) {
                    formData.append(`images[${index}]`, fileData.file);
                } else if (fileData.path) {
                    formData.append(`existingImages[${index}]`, fileData.path);
                }
            });
    
            fetch(form.action, { method: 'POST', body: formData })
                .then((response) => response.json())
                .then((res) => {
                    unLoadButton(btn, btnContent);
                    msg.innerHTML = res.success
                        ? `<p class="alert alert-success"><i class="fas fa-check-circle"></i> ${res.success}</p>`
                        : `<p class="alert alert-danger"><i class="fa-solid fa-circle-info"></i> ${res.error}</p>`;
                    if (res.success) setTimeout(() => location.reload(), 1000);
                })
                .catch(() => {
                    unLoadButton(btn, btnContent);
                    msg.innerHTML = `<div class="alert alert-danger"><i class="fa-solid fa-circle-info"></i> Something went wrong</div>`;
                });
        });
    });
    </script>
    
@stop
