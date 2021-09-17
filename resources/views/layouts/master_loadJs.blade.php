<!--BEGIN:::Base Scripts -->

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="{{ asset('custom/js/vendors.bundle.js?n=1') }}" type="text/javascript"></script>
    <script src="{{ asset('custom/js/scripts.bundle.js?n=1') }}" type="text/javascript"></script>
    <script src="{{ asset('custom/js/global.js?n=1') }}" type="text/javascript"></script>
<!--END:::Base Scripts -->

<!--BEGIN:::DatePicker -->
    <script src="{{ asset('custom/js/bootstrap-datetimepicker.js?n=1') }}" type="text/javascript"></script>
    <script src="{{ asset('custom/js/bootstrap-datepicker.js?n=1') }}" type="text/javascript"></script>
    <script src="{{ asset('custom/js/bootstrap-select.js?n=1') }}" type="text/javascript"></script>
    <script src="{{ asset('custom/js/bootstrap-daterangepicker.js?n=1') }}" type="text/javascript"></script>
<!--END:::DatePicker-->

<!--BEGIN:::flotChart(used by this page)-->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!--END:::flotChart(used by this page)-->

    <script> 
        var appUrl = "{{env('APP_URL')}}";
        var lang = @json(session()->get('locale'));
        
        $(function(){
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');
        });
        $(document).ready(function() {
            BootstrapDatetimepicker.init();
            setDateTimeRangePicker();

            var base_url = window.location.origin;
            var url = window.location.pathname;
            var splitUrl = url.split('/');
            
            if (splitUrl[2] !== undefined) {
                var getMasterMenu = base_url +'/' + splitUrl[1] + '/' + splitUrl[2];
            } else {
                var getMasterMenu = base_url +'/' + splitUrl[1];
            }
            
            $('li.m-menu__item a.m-menu__link.highlight').each(function() {
                var hasSubMenu = $(this).parents('li').hasClass('m-menu__item--submenu');
                var parentSubMenu = $('li.m-menu__item.m-menu__item--submenu');

                if (($(this).attr('href')) == getMasterMenu) {
                    if (hasSubMenu) {
                        $(parentSubMenu).addClass('m-menu__item--open');
                        $(parentSubMenu).find('.m-menu__submenu').removeAttr('style');  
                    } else {
                        $(parentSubMenu).find('.m-menu__item--open').removeClass('m-menu__item--open');
                        $(parentSubMenu).find('.m-menu__submenu').attr("style", "display: none; overflow: hidden;");
                    }
                    
                    $(this).addClass(' active');
                }
            });

            $('li.m-nav__item a.m-nav__link').each(function() {
                if (($(this).attr('href')) == base_url + '/change-language/' + lang) {
                    $(this).addClass(' m-nav__link--active');
                    $(this).parents('li').addClass(' m-nav__item--active');
                    $('#flag-language').attr('src', $(this).find("img").attr('src'));
                }
            });
        });
        
    </script>
    
    <script> 
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
