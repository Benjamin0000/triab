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
                <p>
                    <a href="{{route('eshop.products', [$shop->id, $parent->id])}}" class="btn btn-sm btn-primary">
                    <i class="fa fa-arrow-left"></i> &nbsp;Go Back</a>
                </p>
                
                <h4>{{$shop->name}} | Products</h4>
                <div>{{$shop->storeID}}</div>
            </div>
            
            @foreach ($titles as $title) 
                <span> Category <i class="fa fa-arrow-right"></i> {{$title}}</span>
            @endforeach
            <br>
            <br>
            <h5>Add Product</h5>

            <form id="add_item_form" method="POST" action="{{route('eshop.product.create', $shop->id)}}" enctype="multipart/form-data">    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Product Name</label>
                            <div class="form-control-wrap">
                                <input name="name" type="text" class="form-control" value="" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Cost Price</label>
                            <div>
                                <input type="number" class="form-control" name="cost_price" required step="any">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-block d-sm-none"><br></div>
                        <div class="form-group">
                            <label class="form-label">Selling Price</label>
                            <div>
                                <input type="number" class="form-control" name="selling_price" required step="any">
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="form-group">
                    <label class="form-label" for="">Product Details </label>
                    <textarea name="description" class="form-control desc" placeholder="Share details about this product with your customers."></textarea>
                </div>

                <div class="form-group">
                    <div><b>Product Images</b></div>
                    <small class="text-muted">Select single or multiple images (Max size: 100KB per image).</small>
                    <br>
                    <div class="mb-2">
                        <a type="button" href="javascript:void(0)" id="addImageButton" class="btn btn-primary btn-sm">Add Image</a>
                        <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="visibility:hidden">
                    </div>
                    <div id="imagePreview" class="d-flex flex-wrap"></div>
                </div>

                @csrf
                <input type="hidden" name="shop_id" value="{{$shop->id}}">
                <input type="hidden" name="parent_id" value="{{$parent->id}}">
                
                <div class="msg"></div>
                <div class="form-group">
                    <button class="btn btn-primary">Add Product</button>
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
    const form = document.getElementById('add_item_form'); // Adjust to your form's ID
    const msg = form.querySelector('.msg');
    const btn = form.querySelector('button');
    const selectedFiles = [];

    // Show file input dialog when "Add Image" button is clicked
    addImageButton.addEventListener('click', () => imageInput.click());

    // Handle image selection
    imageInput.addEventListener('change', (event) => {
        const files = Array.from(event.target.files);

        files.forEach((file) => {
            if (file.size > maxFileSize) {
                alert(`${file.name} exceeds the maximum size of 100KB.`);
                return;
            }

            selectedFiles.push(file); // Add file to the array

            const reader = new FileReader();
            reader.onload = (e) => {
                // Create preview container
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('position-relative', 'mr-2', 'mb-2');
                imgContainer.style.display = 'inline-block';

                // Add image preview
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';

                // Add remove button
                const removeBtn = document.createElement('a');
                $(removeBtn).attr('href', 'javascript:void(0)')
                removeBtn.textContent = '×';
                removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'delete-image');
                // Remove image preview and file on click
                removeBtn.addEventListener('click', () => {
                    const index = selectedFiles.indexOf(file);
                    if (index !== -1) selectedFiles.splice(index, 1); // Remove file from array
                    imgContainer.remove();
                });

                imgContainer.appendChild(img);
                imgContainer.appendChild(removeBtn);
                imagePreview.appendChild(imgContainer);
            };
            reader.readAsDataURL(file);
        });

        // Clear input to allow re-selection of the same files
        imageInput.value = '';
    });

    // Handle form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent default form submission
        const btnContent = btn.textContent;
        msg.innerHTML = ''; // Clear any existing messages
        loadButton(btn);

        const formData = new FormData(form);
        selectedFiles.forEach((file, index) => {
            formData.append(`images[${index}]`, file);
        });

        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then((response) => response.json())
        .then((res) => {
            unLoadButton(btn, btnContent);

            if (res.error) {
                msg.innerHTML = `<p class="alert alert-danger">&#9432; ${res.error}</p>`;
            } else if (res.success) {
                msg.innerHTML = `<p class="alert alert-success">${res.success}</p>`;
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        })
        .catch((error) => {
            unLoadButton(btn, btnContent);
            if (!navigator.onLine) {
                msg.innerHTML = `<div class="alert alert-danger"><i class="fa-solid fa-circle-info"></i> Network error: Please check your internet connection.</div>`;
            } else {
                msg.innerHTML = `<div class="alert alert-danger"><i class="fa-solid fa-circle-info"></i> Something went wrong, please try again</div>`;
            }
        });
    });

});

</script>

@stop
