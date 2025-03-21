<!DOCTYPE html>
<!--
	24 News by FreeHTML5.co
	Twitter: https://twitter.com/fh5co
	Facebook: https://fb.com/fh5co
	URL: https://freehtml5.co
-->
<html lang="en" class="no-js">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="/css/frontend/media_query.css" rel="stylesheet" type="text/css"/>
    <link href="/css/frontend/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="/css/frontend/animate.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link href="/css/frontend/owl.carousel.css" rel="stylesheet" type="text/css"/>
    <link href="/css/frontend/owl.theme.default.css" rel="stylesheet" type="text/css"/>
    <!-- Bootstrap CSS -->
    <link href="/css/frontend/style_1.css" rel="stylesheet" type="text/css"/>
    <!-- Modernizr JS -->
    <script src="/js/frontend/modernizr-3.5.0.min.js"></script>
</head>
<body>
 @include('frontend.layouts.header', ['users' => $users, 'news' => $news])
<div class="container-fluid contact_us_bg_img"style="padding: 100px 0px !important;background-color: #b5b2b2;">
    <div class="container">
        <div class="row">
            <a href="#" class="fh5co_con_123"><i class="fa fa-home"></i></a>
            <a href="#" class="fh5co_con pt-2"> Contact Us </a>
        </div>
    </div>
</div>
<div class="container-fluid  fh5co_fh5co_bg_contcat">
    <div class="container">
        <div class="row py-4">
            <div class="col-md-4 py-3">
                <div class="row fh5co_contact_us_no_icon_difh5co_hover">
                    <div class="col-3 fh5co_contact_us_no_icon_difh5co_hover_1">
                        <div class="fh5co_contact_us_no_icon_div"> <span><i class="fa fa-phone"></i></span> </div>
                    </div>
                    <div class="col-9 align-self-center fh5co_contact_us_no_icon_difh5co_hover_2">
                        <span class="c_g d-block">Call Us</span>
                        <span class="d-block c_g fh5co_contact_us_no_text"> +91 9785210571</span>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-4 py-3">
                <div class="row fh5co_contact_us_no_icon_difh5co_hover">
                    <div class="col-3 fh5co_contact_us_no_icon_difh5co_hover_1">
                        <div class="fh5co_contact_us_no_icon_div"> <span><i class="fa fa-envelope"></i></span> </div>
                    </div>
                    <div class="col-9 align-self-center fh5co_contact_us_no_icon_difh5co_hover_2">
                        <span class="c_g d-block">Have any questions?</span>
                        <span class="d-block c_g fh5co_contact_us_no_text">Mahalaji007@gmail.com</span>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-4 py-3">
                <div class="row fh5co_contact_us_no_icon_difh5co_hover">
                    <div class="col-3 fh5co_contact_us_no_icon_difh5co_hover_1">
                        <div class="fh5co_contact_us_no_icon_div"> <span><i class="fa fa-map-marker"></i></span> </div>
                    </div>
                    <div class="col-9 align-self-center fh5co_contact_us_no_icon_difh5co_hover_2">
                        <span class="c_g d-block">Address</span>
                        <span class="d-block c_g fh5co_contact_us_no_text"> Laxmangarh, Sikar, Rajasthan</span>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="container-fluid mb-4">
    <div class="container">
        <div class="col-12 text-center contact_margin_svnit ">
            <div class="text-center fh5co_heading py-2">Contact Us</div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <form class="row" id="fh5co_contact_form">
                    <div class="col-12 py-3">
                        <input type="text" class="form-control fh5co_contact_text_box" placeholder="Enter Your Name" />
                    </div>
                    <div class="col-6 py-3">
                        <input type="text" class="form-control fh5co_contact_text_box" placeholder="E-mail" />
                    </div>
                    <div class="col-6 py-3">
                        <input type="text" class="form-control fh5co_contact_text_box" placeholder="Subject" />
                    </div>
                    <div class="col-12 py-3">
                        <textarea class="form-control fh5co_contacts_message" placeholder="Message"></textarea>
                    </div>
                    <div class="col-12 py-3 text-center"> <a href="#" class="btn contact_btn">Send Message</a> </div>
                </form>
            </div>
            <div class="col-12 col-md-6 align-self-center">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d56462.91204218582!2d74.99976137643486!3d27.811863837162736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396cab11fa93b387%3A0xf9e7d747e969857e!2sLaxmangarh%2C%20Rajasthan%20332311!5e0!3m2!1sen!2sin!4v1738667216265!5m2!1sen!2sin" class="map_sss" allowfullscreen></iframe>

            </div>
        </div>
    </div>
</div>
@include('frontend.layouts.footer', ['categories'=>$categories,'blogcategory'=>$blogcategory,'blogmodify' => $blogmodify, 'news' => $news,'newsmodify'=>$newsmodify,'users' => $users])

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/js/frontend/owl.carousel.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
        crossorigin="anonymous"></script>
<!-- Waypoints -->
<script src="/js/frontend/jquery.waypoints.min.js"></script>
<!-- Main -->
<script src="/js/frontend/main.js"></script>

</body>
