<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
use IntellivoidAccounts\Objects\UserAgentRecord;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('applications'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $Account = $IntellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, (int)$_GET['id']);
    }
    catch (AccountNotFoundException $e)
    {
        print("Account Not Found");
        exit();
    }
    catch (DatabaseException $e)
    {
        print("Database Exception");
        exit();
    }
    catch (Exception $e)
    {
        print($e->getMessage());
        exit();
    }

    if($Account->PersonalInformation->FirstName == null)
    {
        define("USER_FIRST_NAME", "", false);
    }
    else
    {
        define("USER_FIRST_NAME", "value=\"" . htmlspecialchars($Account->PersonalInformation->FirstName, ENT_QUOTES, 'UTF-8') . "\"", false);
    }

    if($Account->PersonalInformation->LastName == null)
    {
        define("USER_LAST_NAME", "", false);
    }
    else
    {
        define("USER_LAST_NAME", "value=\"" . htmlspecialchars($Account->PersonalInformation->LastName, ENT_QUOTES, 'UTF-8') . "\"", false);
    }

    if($Account->PersonalInformation->BirthDate->Year == 0)
    {
        define("USER_BOD_YEAR", "", false);
    }
    else
    {
        define("USER_BOD_YEAR", $Account->PersonalInformation->BirthDate->Year, false);
    }

    if($Account->PersonalInformation->BirthDate->Month == 0)
    {
        define("USER_BOD_MONTH", "", false);
    }
    else
    {
        define("USER_BOD_MONTH", $Account->PersonalInformation->BirthDate->Month, false);
    }

    if($Account->PersonalInformation->BirthDate->Day == 0)
    {
        define("USER_BOD_DAY", "", false);
    }
    else
    {
        define("USER_BOD_DAY", $Account->PersonalInformation->BirthDate->Day, false);
    }

    HTML::importScript('update_account');
    HTML::importScript('render_known_hosts');
    HTML::importScript('render_known_devices');
    HTML::importScript('render_details');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff</title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row profile-page">
                            <div class="col-12">
                                <?PHP HTML::importScript('callbacks'); ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="profile-header text-white">
                                            <div class="d-flex justify-content-around">
                                                <div class="profile-info d-flex align-items-center">
                                                    <img class="rounded-circle img-lg" src="<?PHP HTML::print(getAvatarUrl($Account->PublicID, 'normal')); ?>" alt="profile image">
                                                    <div class="wrapper pl-4">
                                                        <p class="profile-user-name"><?PHP HTML::print($Account->Username); ?></p>
                                                        <div class="wrapper d-flex align-items-center">
                                                            <p class="profile-user-designation"><?PHP HTML::print(date("F j, Y, g:i a", $Account->CreationDate)); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="profile-body">
                                            <ul class="nav tab-switch" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="user-profile-info-tab" data-toggle="pill" href="#user-profile-info" role="tab" aria-controls="user-profile-info" aria-selected="true" style="border-bottom-width: 0;">Profile</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-profile-kh-tab" data-toggle="pill" href="#user-profile-kh" role="tab" aria-controls="user-profile-kh" aria-selected="false" style="border-bottom-width: 0;">Known Hosts</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-profile-kd-tab" data-toggle="pill" href="#user-profile-kd" role="tab" aria-controls="user-profile-kd" aria-selected="false" style="border-bottom-width: 0;">Devices</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-profile-details-tab" data-toggle="pill" href="#user-profile-details" role="tab" aria-controls="user-profile-details" aria-selected="false" style="border-bottom-width: 0;">Details</a>
                                                </li>
                                            </ul>
                                            <div class="row">
                                                <div class="col-md-9 border-right">
                                                    <div class="tab-content tab-body" id="profile-log-switch">
                                                        <div class="tab-pane fade show active pr-3" id="user-profile-info" role="tabpanel" aria-labelledby="user-profile-info-tab">
                                                            <p class="card-description"> Personal info </p>
                                                            <?PHP HTML::importScript('edit_personal_information'); ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-profile-kh" role="tabpanel" aria-labelledby="user-profile-kh-tab">
                                                            <?PHP render_known_hosts($IntellivoidAccounts, $Account->Configuration->KnownHosts->KnownHosts); ?>
                                                        </div>

                                                        <div class="tab-pane fade" id="user-profile-kd" role="tabpanel" aria-labelledby="user-profile-kd-tab">
                                                            <?PHP
                                                                $DeviceResults = array();
                                                                foreach($Account->Configuration->KnownHosts->KnownHosts as $host_id)
                                                                {
                                                                    $Results = $IntellivoidAccounts->getTrackingUserAgentManager()->getRecordsByHost($host_id);
                                                                    foreach($Results as $device)
                                                                    {
                                                                        $device = UserAgentRecord::fromArray($device);
                                                                        $DeviceResults[$device->ID] = $device;
                                                                    }
                                                                }
                                                                render_known_devices($IntellivoidAccounts, $DeviceResults);
                                                            ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-profile-details" role="tabpanel" aria-labelledby="user-profile-details-tab">
                                                            <?PHP render_details($IntellivoidAccounts, $Account);  ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <h5 class="my-4">Roles</h5>
                                                    <div class="d-flex align-items-center">
                                                        <div class="wrapper d-flex align-items-center media-info">
                                                            <i class="mdi mdi-shield icon-md"></i>
                                                            <p class="card-title ml-3 mb-0">Administrator</p>
                                                        </div>
                                                        <div class="wrapper ml-auto action-bar">
                                                            <a class="text-white" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_permission', 'permission' => 'administrator'), true); ?>">
                                                                <i class="mdi mdi-check mr-3"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center border-top mt-3">
                                                        <div class="wrapper d-flex align-items-center media-info pt-3">
                                                            <i class="mdi mdi-security icon-md"></i>
                                                            <p class="card-title ml-3 mb-0">Moderator</p>
                                                        </div>
                                                        <div class="wrapper ml-auto action-bar">
                                                            <a class="text-white" href="#">
                                                                <i class="mdi mdi-check mr-3"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center border-top mt-3">
                                                        <div class="wrapper d-flex align-items-center media-info pt-3">
                                                            <i class="mdi mdi-lifebuoy icon-md"></i>
                                                            <p class="card-title ml-3 mb-0">Support</p>
                                                        </div>
                                                        <div class="wrapper ml-auto action-bar">
                                                            <a class="text-white" href="#">
                                                                <i class="mdi mdi-check mr-3"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    </body>
</html>