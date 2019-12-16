<?PHP
    use DynamicalWeb\HTML;
use DynamicalWeb\Javascript;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff</title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row">
                            <?PHP HTML::importScript('render_stats'); ?>
                        </div>
                        <div class="row">
                            <?PHP HTML::importScript('render_device_usage'); ?>
                            <?PHP HTML::importScript('render_browser_usage'); ?>
                        </div>
                    </div>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <?PHP Javascript::importScript('deviceusage'); ?>
        <?PHP Javascript::importScript('browserusage'); ?>
    </body>
</html>