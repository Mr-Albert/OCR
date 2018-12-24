<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo"
       style="font-size: 16px;">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            <img src="{{ asset('ocr_logo_mini.png') }}" alt="OCR" style="height:35px;">
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img src="{{ asset('ocr_logo_modified.png') }}" alt="OCR Logo" style="height:60px;">
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" id="sidebar_toggle_btn" onclick="$('body').toggleClass('sidebar-collapse'); $('body').trigger('ClassChanged');" role="button" >
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        

    </nav>
</header>


