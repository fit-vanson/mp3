<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{$site->site_name}} Mobile Apps</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bootstrap 4 Mobile App Template">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="shortcut icon" href="{{asset('storage/sites/'. preg_replace('/[^A-Za-z0-9\-._]/',"/",$site->site_image))}}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,500,700|Nunito:400,600,700" rel="stylesheet">

    <!-- FontAwesome JS-->
    <script defer src="{{asset('assets/fontawesome/js/all.min.js')}}"></script>

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-flipster/dist/jquery.flipster.min.css')}}">


    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="{{asset('assets/css/theme.css')}}">

</head>

<body>
<style>
    .hero-section .figure-holder {
        background: url({{asset('storage/sites/'. preg_replace('/[^A-Za-z0-9\-._]/',"/",$site->site_image))}}) no-repeat right top;
        background-size: 375px auto;
        min-height: 600px;
        display: block;
    }
    .web-block{
        display: block;
    }
    @media (min-width: 0) and (max-width: 576px){
        .hero-section .figure-holder {
            background: none;
            background-size: 375px auto;
            min-height: 600px;
            display: block;
        }
        .web-block{
            display: none;
        }
    }
</style>
<header class="header">

    <div class="branding">

        <div class="container position-relative">

            <nav class="navbar navbar-expand-lg" >
                <h1 class="site-logo"><a class="navbar-brand" href="/"><span class="logo-text">{{$site->site_name}}</span></a></h1>
            </nav>

            <!-- // Free Version ONLY -->
            <ul class="social-list list-inline mb-0 position-absolute web-block">
                <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-facebook-f fa-fw"></i></a></li>
                <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-instagram fa-fw"></i></a></li>
            </ul><!--//social-list-->

        </div><!--//container-->

    </div><!--//branding-->


</header><!--//header-->

<section class="hero-section">
    <div class="container">
        <div class="row figure-holder" style="padding-bottom: 0">
            <div class="col-12 col-md-6 pt-3 pt-md-4">
                <h2 class="site-headline font-weight-bold mt-lg-5 pt-lg-5">{{$site->site_header_title}}</h2>
                <div class="site-tagline mb-3">{{$site->site_header_content}}</div>
                <div class="cta-btns"><ul class="app-stores list-unstyled list-inline mx-auto mx-md-0 d-inline-block">
                        <li class="list-inline-item mr-3"><a href="#"><img class="ios" src="{{asset('assets/images/appstore-apple.svg')}}" alt="app-store"></a></li>
                        <li class="list-inline-item"><a href="{{$site->site_chplay_link}}"><img class="android" src="{{asset('assets/images/appstore-android.svg')}}" alt="google play"></a></li>
                    </ul>
                </div>
            </div>
        </div><!--//row-->
    </div>
</section><!--//hero-section-->

<section id="quote" class="testimonial-section py-5">
    <div class="container py-lg-5">
        <h3 class="mb-1 mb-md-5 text-center">Loved by thousands of app users like you</h3>

        <div id="flipster-carousel" class="flipster-carousel pt-md-3">
            <div class="flip-items pb-5">
                @if(isset($site->site_feature_images))
                    @foreach(json_decode($site->site_feature_images,true) as $image)
                        <div class="flip-item text-center text-md-left">
                            <div class="item-inner shadow-lg rounded">
                                <img src="{{asset('storage/featureimages/'.Str::slug($site->site_web).'/'.$image)}}"/>
                            </div><!--//item-inner-->
                        </div><!--//flip-item-->
                    @endforeach
                @endif
            </div><!--//items-wrapper-->
            <div class="pt-5 text-center">
                <a class="btn btn-primary theme-btn font-weight-bold" href="#">Try Now</a>
            </div>

        </div>
    </div><!--//container-->
</section><!--//testimonial-section-->

<section class="cta-section py-5 theme-bg-secondary text-center">
    <div class="container">
        <h3 class="text-white font-weight-bold mb-3">{{$site->site_body_title}}</h3>
        <div class="text-white mx-auto single-col-max-width section-intro">{{$site->site_body_content}}</div>
        <a class="btn theme-btn theme-btn-ghost theme-btn-on-bg mt-4" href="{{$site->site_chplay_link}}">Download  Now</a>
    </div>
</section><!--//cta-section-->

<footer class="footer theme-bg-primary">
    <div class="container py-5 mb-3">
        <div class="row">
            <div class="footer-col col-6 col-lg-4">
                <h4 class="col-heading">Stay Connected</h4>
                <ul class="social-list list-unstyled mb-0">
                    <li class="list-inline-item"><a href="#"><i class="fab fa-instagram fa-fw"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f fa-fw"></i></a></li>

                </ul><!--//social-list-->

{{--                <div class="mb-2">--}}
{{--                    {!! setting('site.company_info') !!}--}}
{{--                </div>--}}
            </div><!--//footer-col-->
            <div class="footer-col col-6 col-lg-4">
                <h4 class="col-heading">Legal</h4>
                <ul class="list-unstyled">
                    <li><a href="{{route('policy')}}">Privacy</a></li>
                </ul>
            </div><!--//footer-col-->
            <div class="footer-col col-6 col-lg-4"></div><!--//footer-col-->
        </div><!--//row-->
    </div><!--//container-->
    <div class="container">
        <hr>
    </div>
    <div class="download-area py-4">
        <div class="container text-center">
            <h3 class="mb-3">{{$site->site_footer_title}}</h3>
            <div class="section-intro mb-4 single-col-max-width mx-auto">{{$site->site_footer_content}} </div>
            <ul class="app-stores list-unstyled list-inline mx-auto  d-inline-block">
                <li class="list-inline-item mr-3"><a href="#"><img class="ios" src="{{asset('assets/images/appstore-apple.svg')}}" alt="app-store"></a></li>
                <li class="list-inline-item"><a href="{{$site->site_chplay_link}}"><img class="android" src="{{asset('assets/images/appstore-android.svg')}}" alt="google play"></a></li>
            </ul>
        </div><!--//container-->
    </div><!--//download-area-->

</footer>


<!-- Javascript -->
<script type="text/javascript" src="{{asset('assets/plugins/jquery-3.4.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/plugins/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- Page Specific JS -->
<script type="text/javascript" src="{{asset('assets/plugins/jquery-flipster/dist/jquery.flipster.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/flipster-custom.js')}}"></script>


</body>
</html>

