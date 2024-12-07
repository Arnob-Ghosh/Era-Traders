@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Product Management</h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Product Return</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="productReturnForm" enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div id="storediv">
                                <div class="row pt-3">
                                    <!-- Product Selection -->
                                    <div class="form-group col-4">
                                        <label for="product" style="font-weight: normal;">Product<span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <select id="product" name="product"
                                            class="form-control w-100 selectpicker d-block mt-2"
                                            data-placeholder="Please select a product">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->productName }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Category Selection -->
                                    <div class="form-group col-4">
                                        <label for="category" style="font-weight: normal;">Category<span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <select id="category"
                                            name="category"class="form-control w-100 selectpicker d-block mt-2"
                                            data-placeholder="Please select a category">
                                        </select>
                                    </div>


                                </div>

                            </div>
                        </form>
                        <div class="table-reponsive">
                            <table id="productReturnTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Available Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Sub Unit/Unit</th>
                                        <th>Total Return</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-5 pt-5">
                            <div class="col-12 text-end ">
                                <button class="btn btn-outline-danger me-2" type="reset" onclick="resetReturnForm()">
                                    <i class="fas fa-eraser"></i> Reset
                                </button>
                                <button id="submitBtn" type="submit" class="btn btn-primary me-4">
                                    <i class="fas fa-save"></i> Submit
                                </button>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function removeRow(button) {
            $(button).closest('tr').remove();
        }

        function resetReturnForm() {
            $('#productReturnForm')[0].reset();
            $('.selectpicker').selectpicker('refresh');
            $('#errorMsg').empty();
            $('#productReturnTable tbody').empty();
        }

        $(document).ready(function() {
            resetReturnForm();
            $('.selectpicker').selectpicker();



        });


        $(document).ready(function() {
            // Trigger when product changes
            $('#product').change(function() {
                var productId = $(this).val();

                // Check if a product was selected
                if (productId) {
                    // Make an AJAX request to get the categories
                    $.ajax({
                        url: '/product/' + productId +
                            '/categories', // Laravel route to get categories
                        type: 'GET',
                        success: function(data) {
                            // Clear the category dropdown
                            $('#category').empty();

                            // Check if categories were returned
                            if (data.length > 0) {
                                // Populate the categories dropdown
                                data.forEach(function(category) {
                                    $('#category').append(new Option(category
                                        .category_name, category.id));
                                });
                            } else {
                                $('#category').append(
                                    '<option>No categories available</option>');
                            }

                            // Reinitialize the selectpicker
                            $('#category').selectpicker('refresh');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching categories:', error);
                            alert('Error fetching categories');
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $('#category').change(function() {
                var productId = $('#product').val();
                var categoryId = $(this).val();
                $('#category').val('').selectpicker('refresh');
                if (productId && categoryId) {
                    // Make an AJAX request to fetch the unit prices
                    $.ajax({
                        url: `/product/${productId}/category/${categoryId}/prices`, // Adjusted Laravel route
                        type: 'GET',
                        success: function(data) {
                            console.log(data)
                            if (data.unit_price && data.unit_price.length > 0) {
                                // Clear the current options in the dropdown
                                // $('#price').empty();


                                // Populate the dropdown with new options
                                data.unit_price.forEach(function(price) {
                                    $('#productReturnTable tbody').append(`
                                        <tr>
                                            <td class="d-none">${price.product_id}</td>
                                            <td>${price.product_name}</td>
                                            <td class="d-none">${price.category_id}</td>
                                            <td>${price.category_name}</td>
                                            <td>${price.unit_quantity} - ${price.unit_name}</td>
                                            <td>${price.unit_price}</td>
                                            <td>${price.sub_unit}</td>
                                            <td><input type="number" name="total_return" id="total_return" class="form-control mt-2"
                                                placeholder="Enter Return Quantity" required></td>
                                            <td class="text-center bg-white">
                                                <button class="btn btn-sm btn-danger btn-link "  
                                                    onclick="removeRow(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    `);
                                });

                                // Refresh the selectpicker if using a plugin
                                // $('#price').selectpicker('refresh');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching unit prices:', error);
                            alert('Error fetching unit prices');
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $('#submitBtn').click(function(event) {
                event.preventDefault();

                // Create an object with the form data
                const formData = {
                    _token: $('input[name="_token"]').val(),
                    products: []
                };

                // Get data from each row in the table
                $('#productReturnTable tbody tr').each(function() {
                    const row = $(this);
                    const product = {
                        product_id: row.find('td:eq(0)').text(),
                        product_name: row.find('td:eq(1)').text(),
                        category_id: row.find('td:eq(2)').text(),
                        category_name: row.find('td:eq(3)').text(),
                        unit_quantity: row.find('td:eq(4)').text().split('-')[0]?.trim() || '',
                        unit_name: row.find('td:eq(4)').text().split('-')[1]?.trim() || '',
                        unit_price: row.find('td:eq(5)').text(),
                        sub_unit: row.find('td:eq(6)').text(),
                        return_quantity: row.find('input[name="total_return"]').val()
                    };
                    formData.products.push(product);
                });
                // Check if there are any products in the table
                if (formData.products.length === 0) {
                    $.notify({
                        icon: 'error',
                        title: 'Error!',
                        message: 'Please add at least one product to return'
                    });
                    return;
                }

                // Check if all required fields are filled for each product
                let hasEmptyFields = false;
                formData.products.forEach((product, index) => {
                    if (!product.product_id || !product.category_id || 
                        !product.unit_quantity || !product.unit_name ||
                        !product.unit_price || !product.sub_unit ||
                        !product.return_quantity) {
                        hasEmptyFields = true;
                       
                    }

                    // Check if return quantity is valid number
                    if (isNaN(product.return_quantity) || product.return_quantity <= 0) {
                        hasEmptyFields = true;
                        $.notify({
                            icon: 'error', 
                            title: 'Error!',
                            message: 'Please enter a valid return quantity for product'
                        });
                    }
                });

                if (hasEmptyFields) {
                    return;
                }

                // Debug log
                console.log('Sending data:', formData);

                $.ajax({
                    url: '/product-in-return',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                    
                        console.log('Success:', response);
                        $.notify({
                            icon: 'success',
                            title: 'Success!',
                            message: 'Product return submitted successfully!',
                        });
                        resetReturnForm();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error Status:', xhr.status);
                        console.error('Error Response:', xhr.responseText);
                        console.error('Error:', error);

                        let errorMessage = 'An error occurred while submitting the form.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $.notify({
                            icon: 'error',
                            title: 'Error!',
                            message: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endsection
