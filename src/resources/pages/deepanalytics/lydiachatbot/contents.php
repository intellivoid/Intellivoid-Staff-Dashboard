<?PHP


    use DeepAnalytics\DeepAnalytics;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Javascript;

    /** @var DeepAnalytics $DeepAnalytics */
    $DeepAnalytics = DynamicalWeb::setMemoryObject('deepanalytics', new DeepAnalytics());

    HTML::importScript('deepanalytics');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/morris/morris.css">
        <title>Intellivoid Staff - @LydiaChatBot Analytics</title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Lydia Session Details</h4>
                                        <div id="deepanalytics_viewer">
                                            <span>Loading</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?PHP HTML::importSection('footer'); ?>

                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/vendors/morris/morris.min.js"></script>
        <script src="/assets/vendors/raphael/raphael-min.js"></script>
        <?PHP Javascript::importScript('rpage'); ?>
        <?PHP Javascript::importScript('deepanalytics'); ?>
    </body>
</html>