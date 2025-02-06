<style>
    .marquee-container {
        width: 83%;
        overflow: hidden;
        box-sizing: border-box;
        margin-left: 35%;
        margin-top: -50px;

    }

    .marquee {
        display: inline-block;
        white-space: nowrap;
        animation: marquee 10s linear infinite;
    }

    .marquee a {
        display: inline-block;
        color: white;
        /* Adjust text color */
        text-decoration: none;
        padding: 10px 20px;
        /* Inner padding */
    }

    span {
        color: red;
        font-size: large;
        text-decoration: none;
        font-weight: bold;
    }

    strong {
        font-size: large;
    }

    @keyframes marquee {
        from {
            transform: translateX(100%);
        }

        to {
            transform: translateX(-100%);
        }
    }
</style>
<div class="container-fluid fh5co_header_bg">
    <div class="container">
        <div class="row">
            <div class="col-12 fh5co_mediya_center"><a href="#" class="color_fff fh5co_mediya_setting"><i
                        @php
use Carbon\Carbon; @endphp
                        class="fa fa-clock-o"></i>&nbsp;&nbsp;&nbsp;{{ Carbon::now()->format('l, j F Y') }}</a>
                <div class="d-inline-block fh5co_trading_posotion_relative"><a href="#"
                        class="treding_btn">Trending</a>
                    <div class="fh5co_treding_position_absolute">
                    </div>
                </div>
                @if (isset($news) && isset($users))
                    <div class="marquee-container">
                        <div class="marquee">
                            @foreach ($news->take(1) as $row)
                                <a href="{{ url('/News/' . $row->slug) }}" class="color_fff fh5co_mediya_setting">
                                    <strong>News : </strong> <span>{{ $row->title }}</span> <img src="{{ asset('css\frontend\new-unscreen.gif') }}" style="height: 30px;"/>
                                </a>
                            @endforeach

                            @foreach ($users->take(1) as $row)
                                <a href="{{ url('/Blogs/' . $row->slug) }}" class="color_fff fh5co_mediya_setting">
                                    <strong>Blog : </strong><span>{{ $row->title }}</span> <img src="{{ asset('css\frontend\new-unscreen.gif') }}" style="height: 30px;"/>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3 fh5co_padding_menu">
                <img src="/css/frontend/erasebg-transformed (1).webp"
                    style="height: 85px; width: 90px; filter: drop-shadow(2px 4px 9px black);" />
            </div>
            <div class="col-12 col-md-9 align-self-center fh5co_mediya_right">
                <div class="text-center d-inline-block">
                    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="/dashboard">Home <span class="sr-only">(current)</span></a>
                    </li>
                </div>
                <div class="text-center d-inline-block">
                    <li class="nav-item {{ request()->is('blogs') ? 'active' : '' }}">
                        <a class="nav-link" href="/blogs">Blog <span class="sr-only">(current)</span></a>
                    </li>
                </div>
                <div class="text-center d-inline-block">
                    <li class="nav-item {{ request()->is('news') ? 'active' : '' }}">
                        <a class="nav-link" href="/news">News <span class="sr-only">(current)</span></a>
                    </li>
                </div>
                <div class="text-center d-inline-block">
                    <li class="nav-item {{ request()->is('contact') ? 'active' : '' }}">
                        <a class="nav-link" href="/contact">Contact <span class="sr-only">(current)</span></a>
                    </li>
                </div>
            </div>
        </div>
    </div>
</div>
