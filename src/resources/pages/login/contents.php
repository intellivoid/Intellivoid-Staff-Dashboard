<?PHP
    use DynamicalWeb\HTML;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
            <?PHP HTML::importSection('navigation'); ?>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper"> </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:../../partials/_footer.html -->
                    <footer class="footer">
                        <div class="container-fluid clearfix">
                          <span class="d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018
                            <a href="http://www.bootstrapdash.com/" target="_blank">Bootstrapdash</a>. All rights reserved.</span>
                                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with
                            <i class="mdi mdi-heart text-danger"></i>
                          </span>
                        </div>
                    </footer>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
    </body>
</html>