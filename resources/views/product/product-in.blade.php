@extends('layouts.admin')

<!-- Bootstrap Select CSS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">

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
                            <h4 class="card-title">Product In</h4>
                        
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="productInForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div id="storediv">
                                <div class="row pt-3">
                                    <!-- Product Selection -->
                                    <div class="form-group col-4">
                                        <label for="product" style="font-weight: normal;">Product<span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <!-- Added class 'd-block' to make the select block-level like input fields -->
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
                                        <select id="category" name="category"
                                            class="form-control w-100 selectpicker  d-block mt-2"
                                            data-placeholder="Please select a category">

                                        </select>
                                    </div>

                                    <!-- Quantity Input -->
                                    <div class="form-group col-4">
                                        <label for="quantity" style="font-weight: normal;">Quantity<span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <input type="number" name="quantity" id="quantity" class="form-control mt-2"
                                            placeholder="Enter Quantity" required>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <!-- Unit Selection -->
                                    <div class="form-group col-4">
                                        <label for="unit" style="font-weight: normal;">Unit<span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <select id="unit" name="unit"
                                            class="form-control w-100 selectpicker d-block mt-2"
                                            data-placeholder="Please select a unit">
                                            <option value="1">Breil</option>
                                        </select>
                                    </div>

                                    <!-- Sub Unit Selection -->
                                    <div class="form-group col-4">
                                        <label for="sub_unit_type" style="font-weight: normal;">Sub Unit Type<span
                                                class="text-danger"><strong></strong>*</span></label>
                                        <select id="sub_unit_type" name="sub_unit"
                                            class="form-control w-100 selectpicker d-block mt-2"
                                            data-placeholder="Please select a sub-unit">
                                            <option value="1">Meter</option>
                                            <option value="2">Kilogram</option>
                                            <option value="3">Liter</option>
                                        </select>
                                    </div>

                                    <!-- Extra Subunit Field (Initially hidden) -->
                                    <div class="form-group col-4" id="extra_subunit_field" style="display: none;">
                                        <label for="subunit" style="font-weight: normal;">Subunit <span
                                            class="text-danger"><strong>*</strong></span></label>
                                        <input type="text" id="subunit" name="subunit"
                                            class="form-control w-100 d-block mt-2" placeholder="Enter subunit value">
                                    </div>


                                    <!-- Unit Price -->
                                    <div class="form-group col-4">
                                        <label for="unit_price" style="font-weight: normal;">Total Price <span
                                                class="text-danger"><strong>*</strong></span></label>
                                        <input type="number" step="0.01" name="price" id="price"
                                            class="form-control mt-2" placeholder="Enter Unit Price" required>
                                    </div>
                                </div>

                                <div class="row mt-2 pt-1">
                                    <div class="col-8"></div>
                                    <div class="col-4">
                                        <button class="btn btn-outline-danger float-right" type="reset" name=""
                                            onclick="resetButton()">
                                            <i class="fas fa-eraser"></i> Reset
                                        </button>
                                        <button id="add_btn" type="button" class="w-30 btn btn-info float-right ml-1"
                                            onclick="productAddToTable()">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                        <h5 class="text-danger float-right mr-5"><strong id="errorMsg"></strong></h5>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="product_IN_table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Sub Unit</th>
                                        <th>Unit Price</th>
                                        <th>Sub Unit Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic rows for added products will appear here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-2 pt-1">
                            <div class="col-8"></div>
                            <div class="col-4">
                                <button id="submitBtn" class="btn btn-primary float-right">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
    <script>
             function resetButton() {
            $('#productInForm')[0].reset();
            $('.selectpicker').selectpicker('refresh');
            $('#errorMsg').empty();

        }
        $(document).ready(function() {
            resetButton()
            $('.selectpicker').selectpicker();


            // Trigger when sub-unit type changes
            $('#sub_unit_type').change(function() {
                var subUnitType = $(this).val();

                // Check if sub-unit type requires extra input
                if (subUnitType) {
                    // Show the extra subunit field
                    $('#extra_subunit_field').show();
                } else {
                    // Hide the extra subunit field if no selection or a specific option is chosen
                    $('#extra_subunit_field').hide();
                }
            });
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

        // Function to add product to table
        function productAddToTable() {
            var product = $('#product').val();
            var category = $('#category').val();
            var quantity = $('#quantity').val();
            var unit = $('#unit').val();
            var sub_unit = $('#sub_unit_type').val();
            var unit_price = $('#price').val();
            var subunit = $('#subunit').val();

            if (product && category && quantity && unit  && unit_price) {
                var tableRow = `<tr>
                    <!-- Visible Columns -->
                    <td>${$('#product option:selected').text()}</td>
                    <td>${$('#category option:selected').text()}</td>
                    <td>${quantity}</td>
                    <td>${$('#unit option:selected').text()}</td>
                    <td>${$('#sub_unit_type option:selected').text()}</td>
                    <td>${unit_price}</td>
                    
                    <!-- Hidden Columns (IDs) -->
                    <td class="d-none">${product}</td> <!-- Product ID -->
                    <td class="d-none">${category}</td> <!-- Category ID -->
                    <td class="d-none">${unit}</td> <!-- Unit ID -->
                    <td class="d-none">${sub_unit}</td> <!-- Subunit ID -->
                    <td>${subunit}</td>

                    <!-- Actions -->
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button>
                    </td>
                </tr>`;

                $('#product_IN_table tbody').append(tableRow);

                // Clear form after adding
                resetButton();
                $('#errorMsg').empty();

            } else {
                $('#errorMsg').text('Please fill in all required fields.');
            }
        }

        // Function to remove row from table
        function removeRow(button) {
            $(button).closest('tr').remove();
        }

        $(document).ready(function() {
    // Submit button click event
    $('#submitBtn').click(function() {
        var tableData = [];

        // Loop through each row in the table
        $('#product_IN_table tbody tr').each(function() {
            var rowData = {
                product: $(this).find('td').eq(6).text(), // Product ID (hidden column)
                category: $(this).find('td').eq(7).text(), // Category ID (hidden column)
                unit_id: $(this).find('td').eq(8).text(), // Unit ID (hidden column)
                unit: $(this).find('td').eq(3).text(), // Unit ID (hidden column)
                sub_unit: $(this).find('td').eq(9).text(), // Subunit ID (hidden column)
                quantity: $(this).find('td').eq(2).text(), // Quantity
                unit_price: $(this).find('td').eq(5).text(), // Unit Price
                product_name: $(this).find('td').eq(0).text(), // Product Name (visible)
                category_name: $(this).find('td').eq(1).text(), // Category Name (visible)
                sub_unit_name: $(this).find('td').eq(4).text(), // Subunit Name (visible)
                subunit: $(this).find('td').eq(10).text() // Subunit Name (visible)
            };

            // Push the row data to the tableData array
            tableData.push(rowData);
        });

        // Check if the table has any rows
        if (tableData.length > 0) {
            // Send the data via AJAX POST request
            $.ajax({
                url: '/product-in', // Your Laravel route for handling the data
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token for Laravel
                    products: tableData // Send the table data
                },
                success: function(response) {
                    console.log(tableData);
                    $('#product_IN_table tbody').empty();
                },
                error: function(xhr, status, error) {
                    console.error('Error submitting data:', error);
                    alert('An error occurred while submitting the data');
                }
            });
        } else {
            alert('Please add products to the table before submitting.');
        }
    });
});

        // Reinitialize the selectpicker (needed for dynamic updates)
    </script>
@endsection
