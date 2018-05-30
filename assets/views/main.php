<? if (!defined('API')) { ?>
    <!DOCTYPE html>
    <html>
        <head>




            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <?
            if (!defined('LANDING')) {
                ?>
            
            <? } ?>
            <!-- Styles -->
            <!-- Bootstrap CSS -->
            <link href="/css/glyphicons.css" rel="stylesheet">
            <link href="/css/glyphicons-bootstrap.css" rel="stylesheet">
            <link href="/css/bootstrap.min.css" rel="stylesheet">

            <!-- Font awesome CSS 
            
            <link href="/css/font-awesome.min.css" rel="stylesheet">		
            <!-- Custom CSS -->
            <!-- Favicon -->
            <link rel="shortcut icon" href="#">

            <script src="/js/jquery-3.1.1.min.js"></script>
            <script src="/js/bootstrap.min.js"></script>
            <script src="/js/bootstrap-select.js"></script>

        </head>
        <script>
            var token = "<?
            if (isset($token)) {
                echo $token->TOKEN;
            } else {
                echo '';
            }
            ?>";
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