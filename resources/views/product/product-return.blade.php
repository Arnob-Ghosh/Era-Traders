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

                                    <!-- Return Quantity Input -->
                                    <div class="form-group col-4">
                                        <label for="quantity" style="font-weight: normal;">Return Quantity<span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <input type="number" name="quantity" id="quantity" class="form-control mt-2"
                                            placeholder="Enter Return Quantity" required>
                                    </div>
                                </div>

                                <div class="row pt-3">

                                    <div class="form-group col-4">
                                        <label for="unit" style="font-weight: normal;">Unit<span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <select id="unit" name="unit"
                                            class="form-control w-100 selectpicker d-block mt-2"
                                            data-placeholder="Please select a unit">
                                            <option value="1">Breil</option>
                                        </select>
                                    </div>
                                    <!-- Reason for Return -->
                                    <div class="form-group col-4">
                                        <label for="reason" style="font-weight: normal;">Reason<span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <input type="text" id="reason" name="reason" class="form-control mt-2"
                                            placeholder="Specify the reason for return">
                                    </div>

                                    <!-- Unit Price -->
                                    <div class="form-group col-4">
                                        <label for="unit_price" style="font-weight: normal;">Unit Price <span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <select id="price" name="price" class="form-control w-100 selectpicker mt-2"
                                            data-placeholder="Select Unit Price" required>
                                            <option value="">Select Unit Price</option>
                                        </select>
                                    </div>


                                  
                                </div>

                                <div class="row mt-2 pt-1">
                                    <div class="col-8"></div>
                                    <div class="col-4">
                                        <button class="btn btn-outline-danger float-right" type="reset"
                                            onclick="resetReturnForm()">
                                            <i class="fas fa-eraser"></i> Reset
                                        </button>
                                        <button id="submitBtn" type="submit" class="btn btn-primary float-right">
                                            <i class="fas fa-save"></i> Submit
                                        </button>
                                    
                                    </div>
                                </div>
                            </div>
                        </form>

                     

                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function resetReturnForm() {
            $('#productReturnForm')[0].reset();
            $('.selectpicker').selectpicker('refresh');
            $('#errorMsg').empty();
        }

        $(document).ready(function() {
            resetReturnForm();
            $('.selectpicker').selectpicker();

            // Calculate total value on quantity/price change
            $('#quantity, #price').on('input', function() {
                var quantity = parseFloat($('#quantity').val()) || 0;
                var price = parseFloat($('#price').val()) || 0;
                $('#total_value').val((quantity * price).toFixed(2));
            });

            // Add product return to table
          
        });

        // Function to remove a row
        function removeRow(button) {
            $(button).closest('tr').remove();
        }
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

                if (productId && categoryId) {
                    // Make an AJAX request to fetch the unit prices
                    $.ajax({
                        url: `/product/${productId}/category/${categoryId}/prices`, // Adjusted Laravel route
                        type: 'GET',
                        success: function(data) {
                            console.log(data)
                            if (data.unit_price && data.unit_price.length > 0) {
                                // Clear the current options in the dropdown
                                $('#price').empty();

                                // Populate the dropdown with new options
                                data.unit_price.forEach(function(price) {
                                    $('#price').append(new Option(price.unit_price, price.unit_price));
                                });

                                // Refresh the selectpicker if using a plugin
                                $('#price').selectpicker('refresh');
                            } else {
                                $('#price').empty();
                                $('#price').selectpicker('refresh');
                                alert('No unit prices available.');
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

        $(document).ready(function () {
    $('#submitBtn').click(function (event) {
        event.preventDefault();
        
        // Create an object with the form data
        const formData = {
            _token: $('input[name="_token"]').val(),
            product: $('#product').val(),
            product_name: $('#product option:selected').text(),
            category: $('#category').val(),
            category_name: $('#category option:selected').text(), 
            unit_id: $('#unit').val(),
            unit: $('#unit option:selected').text(),
            sub_unit: $('#sub_unit').val(),
            quantity: $('#quantity').val(),
            unit_price: $('#price').val()
        };

        // Debug log
        console.log('Sending data:', formData);

        $.ajax({
            url: '/product-in-return',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (response) {
                console.log('Success:', response);
                alert('Product return submitted successfully!');
                resetReturnForm();
            },
            error: function (xhr, status, error) {
                console.error('Error Status:', xhr.status);
                console.error('Error Response:', xhr.responseText);
                console.error('Error:', error);
                
                let errorMessage = 'An error occurred while submitting the form.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    });
});

    </script>
@endsection
