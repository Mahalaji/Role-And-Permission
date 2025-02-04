<div class="container-fluid fh5co_header_bg">
    <div class="container">
        <div class="row">
            <div class="col-12 fh5co_mediya_center"><a href="#" class="color_fff fh5co_mediya_setting"><i
                        class="fa fa-clock-o"></i>&nbsp;&nbsp;&nbsp;Monday, 3 February 2025</a>
                <div class="d-inline-block fh5co_trading_posotion_relative"><a href="#" class="treding_btn">Trending</a>
                    <div class="fh5co_treding_position_absolute"></div>
                </div>
                <a href="#" class="color_fff fh5co_mediya_setting">Instagram’s big redesign goes live with
                    black-and-white app</a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3 fh5co_padding_menu">
                <img src="images/logo.png" alt="img" class="fh5co_logo_width" style="object-fit: cover;" />
                    <nav class="navbar navbar-toggleable-md navbar-light ">
                        <button class="navbar-toggler navbar-toggler-right mt-3" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation"><span
                                class="fa fa-bars"></span></button>
                        <a class="navbar-brand" href="#"><img src="images/logo.png" alt="img" class="mobile_logo_width"
                                style="object-fit: cover;" /></a>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                                    <a class="nav-link" href="/dashboard">Home <span
                                            class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item {{ request()->is('blog*') ? 'active' : '' }}">
                                    <a class="nav-link" href="/blog">Blog <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item {{ request()->is('single*') ? 'active' : '' }}">
                                    <a class="nav-link" href="/single">Single <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item {{ request()->is('contact') ? 'active' : '' }}">
                                    <a class="nav-link" href="/contact">Contact <span
                                            class="sr-only">(current)</span></a>
                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>
        </div>
    </div>
</div>