<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('css/bootstrap.min.css') }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Your invoices">
        <meta name="author" content="Scott Huson">
        <!-- Favicons -->
        <!--<link rel="apple-touch-icon-precomposed" sizes="57x57" href="http://brownbytes.org/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://brownbytes.org/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://brownbytes.org/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://brownbytes.org/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon-precomposed" sizes="60x60" href="http://brownbytes.org/apple-touch-icon-60x60.png" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="http://brownbytes.org/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon-precomposed" sizes="76x76" href="http://brownbytes.org/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="http://brownbytes.org/apple-touch-icon-152x152.png" /> -->
        <link rel="icon" type="image/png" href="http://brownbytes.org/img/favicon/favicon-196x196.png" sizes="196x196" />
        <link rel="icon" type="image/png" href="http://brownbytes.org/img/favicon/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/png" href="http://brownbytes.org/img/favicon/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="http://brownbytes.org/img/favicon/favicon-16x16.png" sizes="16x16" />
        <link rel="icon" type="image/png" href="http://brownbytes.org/img/favicon/favicon-128.png" sizes="128x128" />
        <meta name="application-name" content="BB | Welcome - Brown Bytes: Free Food For All"/>
        <!-- <meta name="msapplication-TileColor" content="#FFFFFF" />
        <meta name="msapplication-TileImage" content="http://brownbytes.org/mstile-144x144.png" />
        <meta name="msapplication-square70x70logo" content="http://brownbytes.org/mstile-70x70.png" />
        <meta name="msapplication-square150x150logo" content="http://brownbytes.org/mstile-150x150.png" />
        <meta name="msapplication-wide310x150logo" content="http://brownbytes.org/mstile-310x150.png" />
        <meta name="msapplication-square310x310logo" content="http://brownbytes.org/mstile-310x310.png" /> -->

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129142756-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-129142756-1');
        </script>

    </head>
    <body>
        {{ content() }}
        {{ javascript_include('js/jquery.min.js') }}
        {{ javascript_include('js/bootstrap.min.js') }}
        {{ javascript_include('js/utils.js') }}
    </body>
</html>