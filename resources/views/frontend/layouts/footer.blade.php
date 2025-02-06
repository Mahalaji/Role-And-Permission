<div class="container-fluid fh5co_footer_bg pb-3">
    <div class="container animate-box">
        <div class="row">
            <div class="col-12 spdp_right py-5"><img src="/css/frontend/erasebg-transformed (1).webp" alt="img"
                    class="footer_logo" style="height: 85px; width: 90px; filter: drop-shadow(2px 4px 9px black);" />
            </div>
            <div class="clearfix"></div>
            <div class="col-12 col-md-4 col-lg-3">
                <div class="footer_main_title py-3"> About</div>
                <div class="footer_sub_about pb-3">News delivers factual, timely reports on current events, while a blog
                    shares personal insights, opinions, or expertise on various topics in a conversational style.
                </div>
                <div class="footer_mediya_icon">
                    <div class="text-center d-inline-block"><a class="fh5co_display_table_footer">
                            <div class="fh5co_verticle_middle"><i class="fa fa-linkedin"></i></div>
                        </a></div>
                    <div class="text-center d-inline-block"><a class="fh5co_display_table_footer">
                            <div class="fh5co_verticle_middle"><i class="fa fa-google-plus"></i></div>
                        </a></div>
                    <div class="text-center d-inline-block"><a class="fh5co_display_table_footer">
                            <div class="fh5co_verticle_middle"><i class="fa fa-twitter"></i></div>
                        </a></div>
                    <div class="text-center d-inline-block"><a class="fh5co_display_table_footer">
                            <div class="fh5co_verticle_middle"><i class="fa fa-facebook"></i></div>
                        </a></div>
                </div>
            </div>
            <div class="col-12 col-md-3 col-lg-2">
            </div>
            <div class="col-12 col-md-5 col-lg-3 position_footer_relative">
                <div class="footer_main_title py-3"> Most Viewed News</div>
                @foreach ($news->take(3) as $row)
                    <div class="footer_makes_sub_font"> {{ $row->created_at }}</div>
                    <a href="{{ url('/News/' . $row->slug) }}" class="footer_post pb-4"> {{ $row->title }} </a>
                @endforeach

                <div class="footer_position_absolute"><img src="\css\frontend\Group 556.png" alt="img"
                        class="width_footer_sub_img" style="height: 170px;" /></div>
                         <div class="footer_main_title py-3"> Most Viewed Blog</div>
                @foreach ($users->take(3) as $row)
                    <div class="footer_makes_sub_font"> {{ $row->created_at }}</div>
                    <a href="{{ url('/Blogs/' . $row->slug) }}" class="footer_post pb-4"> {{ $row->title }} </a>
                @endforeach
                <div class="footer_position"><img src="\css\frontend\Group 556.png" alt="img"
                        class="width_footer_sub_img" style="margin-top: -90%;margin-left: -20px;height: 170px;top: 110px;" /></div>
            </div>
            <div class="col-12 col-md-12 col-lg-4 ">
                <div class="footer_main_title py-3"> Last Modified Blogs</div>
                @foreach ($blogmodify->take(6) as $row)
                    <a href="{{ url('/Blogs/' . $row->slug) }}" class="footer_img_post_6"><img src="{{ asset($row->image) }}"
                            alt="img" style="object-fit: cover;" /></a>
                @endforeach
                <div class="footer_main_title py-3"> Last Modified News</div>
                @foreach ($newsmodify->take(6) as $newss)
                    <a href="{{ url('/News/' . $newss->slug) }}" class="footer_img_post_6"><img src="{{ asset($newss->news_image) }}"
                            alt="img" style="object-fit: cover;" /></a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="container-fluid fh5co_footer_right_reserved">
    <div class="container">
        <div class="row  ">
            <div class="col-12 col-md-6 py-4 Reserved"> © Copyright 2025, All rights reserved. Design by Mahala ji</div>
            <div class="col-12 col-md-6 spdp_right py-4">
                <a href="/dashboard" class="footer_last_part_menu">Home</a>
                <a href="/blogs" class="footer_last_part_menu">Latest Blogs</a>
                <a href="/contact" class="footer_last_part_menu">Contact</a>
                <a href="/news" class="footer_last_part_menu">Latest News</a>
            </div>
        </div>
    </div>
</div>

<div class="gototop js-top">
    <a href="#" class="js-gotop"><i class="fa fa-arrow-up"></i></a>
</div>
