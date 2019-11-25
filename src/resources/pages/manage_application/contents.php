<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('applications'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $Application = $IntellivoidAccounts->getApplicationManager()->getApplication(
            ApplicationSearchMethod::byId, $_GET['id']
        );
    }
    catch (ApplicationNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('applications', array('callback' => '104')));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('applications', array('callback' => '105')));
    }

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

                    </div>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
    </body>
</html>