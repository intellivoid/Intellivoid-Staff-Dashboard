<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        HTML::importScript('authenticate');
    }

?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('login_headers'); ?>
        <title>Intellivoid Staff - Authentication</title>
    </head>

    <body>
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper d-flex align-items-center auth area theme-one">
                    <div class="row w-100 mx-auto">
                        <?PHP HTML::importSection('background_animations'); ?>
                        <div class="col-lg-4 mx-auto">
                            <div class="auto-form-wrapper">
                                <h1 class="text-center">
                                    <img src="/assets/images/iv_logo.svg" alt="Intellivoid Blue Logo" class="img-sm rounded-circle"/>
                                    Intelli<b>void</b>
                                </h1>
                                <div name="callback_alert" id="callback_alert">
                                    <?PHP HTML::importScript('callbacks'); ?>
                                </div>

                                <div class="border-bottom pt-3"></div>

                                <form class="pb-4" id="authentication_form" action="<?PHP DynamicalWeb::getRoute('auth/login', [], true); ?>" method="POST" name="authentication_form">
                                    <div class="form-group pt-4">
                                        <label for="authentication_code" class="label">Authentication Code</label>
                                        <div class="input-group">
                                            <input name="authentication_code" id="authentication_code" autocomplete="off" type="text" class="form-control" placeholder="OTL Authentication Code" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="mdi mdi-lock"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-danger submit-btn btn-block" value="Authenticate">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('login_scripts'); ?>
    </body>
</html>
