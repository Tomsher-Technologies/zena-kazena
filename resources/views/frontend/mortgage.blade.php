@extends('frontend.layouts.app')
@section('content')
    <!-- About page -->
    <div class="about-page">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Title -->
            <h1 class="about-page__title">Mortgage</h1>
            <!-- End title -->
            <!-- Description -->
            <div class="about-page__description">A mortgage is a loan used to purchase real estate, where the property itself serves as collateral. It allows buyers to finance the majority of the purchase price while making an initial down payment. Borrowers agree to repay the loan in regular installments over a set term, typically 15 to 30 years, with each payment including both principal and interest. Mortgages can have fixed or adjustable interest rates, influencing the total cost of the loan. If the borrower fails to meet the repayment obligations, the lender has the right to foreclose on the property. Mortgages are essential for most homebuyers, providing a practical way to own property without needing the full purchase price upfront.</div>
            <!-- End description -->
            <div class="mb-5 text-center">
                <a href="#" class="eighth-button" data-toggle="modal" data-target="#mortgageModal">Start mortgage</a>
            </div>

        </div>
        <!-- End container -->
        <!-- About company -->
        <div class="about-company">
            <!-- Container -->
            <div class="container container--type-2">
                <!-- Row -->
                <div class="row">
                    <!-- Company -->
                    <div class="col-lg-6 about-company__item">
                        <!-- Image -->
                        <div class="about-company__image">
                            <img alt="Image" data-sizes="auto"
                                data-srcset="{{ asset('assets/images/mortgage.jpg') }}"
                                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                class="lazyload" />
                        </div>
                        <!-- End image -->
                        <!-- Title -->
                        <h2 class="about-company__title">How Mortgages Work</h2>
                        <!-- End title -->
                        <!-- Description -->
                        <div class="about-company__description">
                        
                            <p>Individuals and businesses use mortgages to buy real estate without paying the entire purchase price upfront. The borrower repays the loan plus interest over a specified number of years until they own the property free and clear. Most traditional mortgages are fully amortized. This means that the regular payment amount will stay the same, but different proportions of principal vs. interest will be paid over the life of the loan with each payment. Typical mortgage terms are for 15 or 30 years, but some mortages can run for longer terms.

                                Mortgages are also known as liens against property or claims on property. If the borrower stops paying the mortgage, the lender can foreclose on the property.
                                
                                For example, a residential homebuyer pledges their house to their lender, which then has a claim on the property. This ensures the lender’s interest in the property should the buyer default on their financial obligation. In the case of foreclosure, the lender may evict the residents, sell the property, and use the money from the sale to pay off the mortgage debt.
                            </p>
                        </div>
                        <!-- End description -->

                        {{-- <div class="mt-5 text-left">
                            <a href="#" class="eighth-button " data-toggle="modal" data-target="#mortgageModal">Start mortgage</a>
                        </div> --}}
                    </div>
                    <!-- End company -->
                    <!-- Leader -->
                    <div class="col-lg-6 about-company__item">
                        <!-- Image -->
                        <div class="about-company__image">
                            <img alt="Image" data-sizes="auto"
                                data-srcset="{{ asset('assets/images/mortgage1.jpg') }}"
                                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                class="lazyload" />
                        </div>
                        <!-- End image -->
                        <!-- Title -->
                        <h2 class="about-company__title">The Mortgage Process</h2>
                        <!-- End title -->
                        <!-- Description -->
                        <div class="about-company__description">
                            <p>Would-be borrowers begin the process by applying to one or more mortgage lenders. The lender will ask for evidence that the borrower is capable of repaying the loan. This may include bank and investment statements, recent tax returns, and proof of current employment. The lender will generally run a credit check as well.

                                If the application is approved, the lender will offer the borrower a loan of up to a certain amount and at a particular interest rate. Homebuyers can apply for a mortgage after they have chosen a property to buy or even while they are still shopping for one, thanks to a process known as pre-approval. Being pre-approved for a mortgage can give buyers an edge in a tight housing market because sellers will know that they have the money to back up their offer.
                                
                                Once a buyer and seller agree on the terms of their deal, they or their representatives will meet at what’s called a closing. This is when the borrower makes their down payment to the lender. The seller will transfer ownership of the property to the buyer and receive the agreed-upon sum of money, and the buyer will sign any remaining mortgage documents. The lender may charge fees for originating the loan (sometimes in the form of points) at the closing.</p>

                        </div>
                        <!-- End description -->


                        <div class="mt-5 text-left">
                            <a href="#" class="eighth-button " data-toggle="modal" data-target="#mortgageModal">Start mortgage</a>
                        </div>
                    </div>
                    <!-- End leader -->
                </div>
                <!-- End row -->
            </div>
            <!-- End container -->
        </div>
        <!-- End about company -->
    </div>

    <div id="mortgageModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mortgageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mortgageModalLabel">Mortgage Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="mortgageForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group  m-2">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group m-2">
                            <label for="email">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group m-2">
                            <label for="phone">Phone<span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group m-2">
                            <label for="description">Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group m-2">
                            <label for="image">Upload Emirates ID<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="idimage" name="idimage" accept="image/*">
                        </div>
                        <div class="form-group m-2">
                            <label for="image">Upload Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    
    <script type="text/javascript">
        $(document).ready(function () {
            $("#mortgageForm").on("submit", function (e) {
                e.preventDefault(); // Prevent the page from reloading
            });

            $("#mortgageForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    description: {
                        required: true,
                        minlength: 10
                    },
                    idimage: {
                        required: true,
                    },
                    
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Name must be at least 3 characters"
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email address"
                    },
                    phone: {
                        required: "Please enter your phone number",
                        digits: "Only digits are allowed",
                        minlength: "Phone number must be at least 10 digits",
                        maxlength: "Phone number must not exceed 15 digits"
                    },
                    description: {
                        required: "Please enter a description",
                        minlength: "Description must be at least 10 characters"
                    },
                    idimage: {
                        required: "Please upload an Emirates ID image",
                        accept: "Only image files are allowed"
                    },
                    image: {
                        accept: "Only image files are allowed"
                    }
                },
                submitHandler: function (form) {
                    event.preventDefault();
                    // Perform an AJAX request or submit the form
                    $.ajax({
                        url: "{{ route('submit-mortgage') }}", // Replace with your route
                        type: "POST",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.status == true) {
                                toastr.success(response.message, "{{trans('messages.success')}}");
                                $('#mortgageForm')[0].reset(); // Reset the form
                                $('#mortgageModal').modal('hide'); // Hide the modal
                            } else {
                                toastr.error(response.message, "{{trans('messages.error')}}");
                            }
                        },
                        error: function (xhr) {
                            toastr.error("{{trans('messages.something_went_wrong')}}", "{{trans('messages.error')}}");
                        }
                    });
                }
            });

            // Reset validation on modal close
            $('#mortgageModal').on('hidden.bs.modal', function () {
                $("#mortgageForm")[0].reset(); // Clear the form
                $("#mortgageForm").validate().resetForm(); // Reset validation
            });
        });


    </script>
@endsection
