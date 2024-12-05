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
            <div class="about-page__description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur
                accusamus fuga velit cumque consectetur et commodi earum voluptate? Minus tempore, esse illo qui quibusdam
                et delectus illum assumenda rerum culpa. Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum nemo
                voluptates commodi quasi nulla pariatur magni excepturi, error nostrum voluptatum porro tempore eveniet
                quam! Perspiciatis illo ratione delectus eos molestiae!</div>
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
                                data-srcset="assets/images/mortgage.jpg 735w,
                assets/images/mortgage.jpg 1470w"
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
                            <p><span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium obcaecati,
                                    excepturi necessitatibus numquam tenetur molestias dolorem ex, totam aliquam,
                                    consequuntur omnis sint deserunt repudiandae sit aspernatur corrupti nisi vel
                                    odit!</span></p>
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident ipsam ipsa similique
                                veniam aliquid minus, incidunt cupiditate aut quasi est unde explicabo perspiciatis, enim
                                illum debitis temporibus placeat, aperiam qui?
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem nemo similique, quae
                                architecto quam sapiente dolores eum, voluptatum adipisci tempore cum ab ducimus? Numquam,
                                corporis sequi doloribus a suscipit nemo?
                            </p>
                        </div>
                        <!-- End description -->

                        <div class="mt-5 text-left">
                            <a href="#" class="eighth-button ">Start mortgage</a>
                        </div>
                    </div>
                    <!-- End company -->
                    <!-- Leader -->
                    <div class="col-lg-6 about-company__item">
                        <!-- Image -->
                        <div class="about-company__image">
                            <img alt="Image" data-sizes="auto"
                                data-srcset="assets/images/mortgage1.jpg 735w,
                assets/images/mortgage1.jpg 1470w"
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
                            <a href="#" class="eighth-button ">Start mortgage</a>
                        </div>
                    </div>
                    <!-- End leader -->
                </div>
                <!-- End row -->
            </div>
            <!-- End container -->
        </div>
        <!-- End about company -->







        <!-- Footer collection -->
        <footer class="modern-footer collection-footer">

            <!-- Newsletter -->
            <div class="blog-with-sidebar__newsletter">
                <!-- Container -->
                <div class="container">
                    <!-- Row -->
                    <div class="row blog-newsletter">
                        <div class="col-lg-12">
                            <!-- Newsletter Title -->
                            <h3 class="blog-newsletter__title font-family-jost text-center">Newsletter</h3>
                            <!-- End newsletter title -->
                        </div>

                        <div class="col-lg-6">
                            <p class="newsletter-text-area">Sign up to be aware of our new products and all developments!
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <!-- Newsletter form -->
                            <form class="blog-newsletter__form">
                                <input type="email" placeholder="Your email address" class="blog-newsletter__input" />
                                <button type="submit" class="blog-newsletter__submit">Subscribe</button>
                            </form>
                            <!-- End newsletter form -->
                        </div>
                    </div>
                    <!-- End row -->
                </div>
                <!-- End container -->
            </div>
            <!-- End newsletter -->

            <!-- Container -->
            <div class="container">

                <!-- Menu -->
                <ul class="modern-footer__menu">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Shop </a></li>
                    <li><a href="#">Terms & Conditions </a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Careers </a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <!-- End menu -->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-4 mobile-order-2">
                        <!-- Social -->
                        <div class="modern-footer__social">
                            <p>follow us</p>
                            <ul>
                                <li><a href="#"><img src="assets/images/instagram.svg" alt=""></a></li>
                                <li><a href="#"><img src="assets/images/facebook.svg"></a></li>
                                <li><a href="#"><img src="assets/images/youtube.svg"></a></li>
                                <li><a href="#"><img src="assets/images/LinkedIn.svg"></a></li>
                            </ul>
                        </div>
                        <!-- End social -->
                    </div>
                    <div class="col-lg-4">
                        <!-- Logo -->
                        <div class="modern-footer__logo">
                            <img width="250" src="assets/images/logow.png" alt="">
                        </div>
                        <!-- End logo -->
                        <!-- Address -->
                        <div class="modern-footer__address">

                            <ul>
                                <li><a href="#"><img width="40" src="assets/images/email.svg" alt=""></a>
                                </li>
                                <li><a href="#"><img width="40" src="assets/images/chat.svg"></a></li>
                                <li><a href="#"><img width="40" src="assets/images/phone.svg"></a></li>
                                <li><a href="#"><img width="40" src="assets/images/visit.svg"></a></li>
                            </ul>
                        </div>
                        <!-- End address -->
                        <!-- Payment -->
                        <div class="modern-footer__payment d-none d-lg-block">
                            <img src="assets/images/payment.svg" alt="Payment" />
                        </div>
                        <!-- End payment -->
                    </div>
                    <div class="col-lg-4 mobile-order-3">
                        <!-- Currency -->
                        <div class="modern-footer__currency">
                            <p>JOIN WITH US</p>

                            <div class="become_promotor" bis_skin_checked="1"><a href="#"><i
                                        class="lnr lnr-bullhorn"></i>Become Promotor
                                </a></div>
                            <div class="become_partner" bis_skin_checked="1"><a href="#"><i
                                        class="lnr lnr-thumbs-up"></i>Become Partner
                                </a></div>



                        </div>
                        <!-- End currency -->
                    </div>
                </div>
                <!-- End row -->
                <!-- Payment -->
                <div class="modern-footer__payment d-block d-lg-none">
                    <img src="assets/images/payment.svg" alt="Payment" />
                </div>
                <!-- End payment -->


                <!-- Copyright -->
                <div class="modern-footer__copyright">© 2023 ZENA & KAZENA. All rights reverved. Design By TOMSHER</div>
                <!-- End copyright -->

            </div>

            <!-- End container -->
        </footer>
        <!-- End footer collection -->




        <!-- Search popup -->
        <div class="search-popup js-search-popup">
            <!-- Search close -->
            <div class="search-popup__close">
                <a href="#" class="js-close-search-popup"><i class="lnr lnr-cross"></i></a>
            </div>
            <!-- End search close -->
            <!-- Container -->
            <div class="container container--type-2">
                <!-- Search title -->
                <h5 class="search-popup__title">Search</h5>
                <!-- End search title -->
                <!-- Search categories -->
                <ul class="search-popup__categories">
                    <li><a href="#" class="active">All</a></li>
                    <li><a href="#">Rings</a></li>
                    <li><a href="#">Bracelets</a></li>
                    <li><a href="#">Bangles</a></li>
                    <li><a href="#">Necklaces</a></li>
                </ul>
                <!-- End search categories -->
                <!-- Search form -->
                <form class="search-popup__form">
                    <!-- Search input -->
                    <input type="text" class="search-popup__input" placeholder="Search here..." />
                    <!-- End search input -->
                </form>
                <!-- End search form -->
                <!-- Search results -->
                <div class="search-popups__results">
                    <!-- Results heading -->
                    <h6 class="search-popup__results-heading">Search results</h6>
                    <!-- End results heading -->
                    <!-- Results -->
                    <div class="search-popups__results-products d-flex">
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image2.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">18K Red & Black Gold Diamond
                                    Mismatched Earrings</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">AED 2500.00</div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image1.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">18K Yellow & White Gold Diamond
                                    Ring</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">AED 6500.00</div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image3.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">Black Diamond Necklace</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">AED 3500.00</div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image1.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">18K Yellow & White Gold Diamond
                                    Ring</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">AED 1999.00</div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image4.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">18K Red & Black Gold Diamond
                                    Mismatched Earrings</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">
                                <!-- Price new -->
                                <span class="result-product__price-new">AED 2500.00</span>
                                <!-- End price new -->
                                <!-- Price old -->
                                <span class="result-product__price-old">AED 5000.00</span>
                                <!-- End price old -->
                            </div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                    </div>
                    <!-- End results -->
                    <!-- Results action -->
                    <div class="search-popup__results-action">
                        <a href="#" class="fifth-button">All results (12)</a>
                    </div>
                    <!-- End results actions -->
                </div>
                <!-- End search results -->
            </div>
            <!-- End container -->
        </div>
        <!-- End search popup -->
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
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone<span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Upload Emirates ID<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="idimage" name="idimage" accept="image/*">
                        </div>
                        <div class="form-group">
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
                            console.log(response);
                            alert(response.message);
                            $('#mortgageForm')[0].reset(); // Reset the form
                            $('#mortgageModal').modal('hide'); // Hide the modal
                        },
                        error: function (xhr) {
                            alert(response.message);
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
