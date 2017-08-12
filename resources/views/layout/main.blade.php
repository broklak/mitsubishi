<!DOCTYPE html>
<html>
<head>
    <!-- META AND TITLE
    ================================================== -->
    @include("layout.meta")
    
    <!-- CSS
    ================================================== -->
    @include("layout.css")
</head>
<body class="hold-transition skin-red sidebar-mini">
<div class="wrapper">

    <!-- HEADER
    ================================================== -->
    @include("layout.header")

    <!-- SIDEBAR
    ================================================== -->
    @include("layout.sidebar")


    @yield("content")
  

    <!-- FOOTER
    ================================================== -->
    @include("layout.footer")  

</div>
    <!-- JS
    ================================================== -->
    @include("layout.js")

</body>
</html>
