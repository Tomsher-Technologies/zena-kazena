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
                        <h2 class="about-company__title">First time buyer, remortgage or self-employed - we can help! We are
                            your fees-free broker.</h2>
                        <!-- End title -->
                        <!-- Description -->
                        <div class="about-company__description">
                        
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident ipsam ipsa similique
                                veniam aliquid minus, incidunt cupiditate aut quasi est unde explicabo perspiciatis, enim
                                illum debitis temporibus placeat, aperiam qui?
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem nemo similique, quae
                                architecto quam sapiente dolores eum, voluptatum adipisci tempore cum ab ducimus? Numquam,
                                corporis sequi doloribus a suscipit nemo?
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
                        <h2 class="about-company__title">Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe sit
                            deserunt dolor, nobis enim ab. Fugiat sapiente dolorem possimus dolorum earum ducimus veniam
                            quam omnis, aliquid, vel debitis blanditiis doloremque.</h2>
                        <!-- End title -->
                        <!-- Description -->
                        <div class="about-company__description">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod explicabo reprehenderit
                                voluptatem omnis fugit eligendi. Ipsum hic labore deleniti recusandae sunt, neque libero
                                necessitatibus iste inventore quas ratione. Doloremque, praesentium.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus fugit nobis repellat
                                officiis commodi! Suscipit numquam, deleniti blanditiis sunt maxime porro praesentium omnis
                                sint molestias illo accusamus, aliquid iste quidem? Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Corporis, illo quae! Tempore obcaecati illo repellendus ratione corporis
                                accusamus facere rem dignissimos eveniet fugiat placeat incidunt nulla odit autem, sunt
                                dolore. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minima error, optio ipsum
                                iusto tenetur quidem veniam accusantium itaque, quibusdam inventore voluptates corporis
                                molestias quos aliquid quae facilis esse eum cum!</p>

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
