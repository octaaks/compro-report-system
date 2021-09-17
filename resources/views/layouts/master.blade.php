<!-- BEGIN:::Head -->

<head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Compro Reporting - @yield('title') </title>
    <meta name="description" content="Compro Engine V2">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!--BEGIN:::Web font -->
    <link rel="stylesheet" href="{{ asset('custom/css/font/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/font/flaticon.css') }}">
    <script src="{{ asset('custom/js/webfont.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--END:::Web font -->

    <!--BEGIN:::Base Styles -->
    <link href="{{ asset('custom/css/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('custom/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="https://d181jp1bcm67qh.cloudfront.net/images/favicon.png" />
    <link href="{{ asset('custom/css/custom_css.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <!--END:::Base Styles -->
</head>
<!-- END:::Head -->

<!-- BEGIN:::Body -->

<body
    class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <!-- BEGIN::: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <!-- BEGIN:: Header -->
        @extends('layouts.master_header')
        <!-- END:: Header -->

        <!-- BEGIN:::Body -->
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
            <!-- BEGIN:: Left side -->
            @extends('layouts.master_body_leftSide')
            <!-- END:: Left side -->

            <div class="m-grid__item m-grid__item--fluid m-wrapper">

                <!-- BEGIN:: sub_header -->
                <!-- Begin sub_header -->

                <!-- <div style="margin-top:20px" class="m-sub_header">
                <div class="d-flex align-items-center">
                        <div class="mr-auto">
                            <h3 class="m-sub_header__title m-sub_header__title--separator" style="border-right: 0px;">@yield('title')</h3>
                        </div>
                    </div> 
                </div>-->

                <input type="hidden" disabled id="no_record_img_url" value="https://d181jp1bcm67qh.cloudfront.net/images/content/no-record-found.svg">
                <!-- End sub_header -->
                <!-- END:: sub_header -->

                @yield('content')

            </div>
        </div>
        <!-- END::: Body -->

        <!-- BEGIN:::Footer -->

        <footer class="m-grid__item	m-footer " style="height: 44px;">
				<div class="m-container m-container--fluid m-container--full-height m-page__container">
					<div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
						<div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
							<span class="m-footer__copyright">
								© 2015<script>new Date().getFullYear()>2010&&document.write("-"+new Date().getFullYear());</script>
								<a href="https://compro.id/" class="m-link">Compro</a>
							</span>
						</div>

					</div>
				</div>
			</footer>

        <!-- END:::Footer -->
    </div>
    <!-- END::: Page -->


    <!-- BEGIN:::Scroll Top -->
    <div id="m_scroll_top" class="m-scroll-top">
        <i class="fas fa-arrow-up"></i>
    </div>
    <!-- END:::Scroll Top -->

</body>
<!-- END:::Body -->
