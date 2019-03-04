<? if (!defined('API')) { ?>
<!DOCTYPE html>
<html>
    <head>




        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="yandex-verification" content="8a2bd8bb0e6ac9d6" />
        <meta name="google-site-verification" content="X0D7gRHyjcrA2PUmJ2UdntMP9n6V-1tjfRJ8YoCftps" />
        <?
        if (!defined('LANDING')) {
        ?>

        <? } ?>
        <!-- Styles -->
        <!-- Bootstrap CSS -->
        <link href="/css/glyphicons.css" rel="stylesheet">
        <link href="/css/glyphicons-bootstrap.css" rel="stylesheet">
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">

        <!-- Font awesome CSS 
        
        <link href="/css/font-awesome.min.css" rel="stylesheet">		
        <!-- Custom CSS -->
        <!-- Favicon -->
        <link rel="shortcut icon" href="#">

        <script src="/js/jquery-3.1.1.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/bootstrap-select.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    </head>
    <script>
        var token = "<?
                if (isset($token)) {
        echo $token -> TOKEN;
        } else {
        echo '';
        }
        ?> ";
        $(document).ready(function () {
          $('#events').DataTable();
        });
        $(document).ready(function () {
          $('#persons').DataTable();
        });
    </script>
    <body>

        <? require_once 'header.php'; ?>
        <?
        if (!defined('LANDING')) {
        ?>
        <div id="main" class="container" style="">
            <? } ?>

            <?
            }

            //Include the subview

            include($subview . '.php');
            ?>
            <? if (!defined('API')) { ?>

            <?
            if (!defined('LANDING')) {
            ?>
        </div>
        <? } ?>
    </body>
</html>
<?
}?>