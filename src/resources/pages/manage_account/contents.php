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
                                            </ul>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="tab-content tab-body" id="profile-log-switch">
                                                        <div class="tab-pane fade show active pr-3" id="user-profile-info" role="tabpanel" aria-labelledby="user-profile-info-tab">
                                                            <p class="card-description"> Personal info </p>
                                                            <form method="POST" action="<?PHP DynamicalWeb::getRoute('manage_account', array('action'=>'update_information', 'id'=>$_GET['id']), true); ?>">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-3 col-form-label" for="first_name">First Name</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text"<?PHP HTML::print(USER_FIRST_NAME, false); ?> name="first_name" id="first_name" class="form-control" placeholder="None">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-3 col-form-label" for="last_name">Last Name</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text"<?PHP HTML::print(USER_LAST_NAME, false); ?> name="last_name" id="last_name" class="form-control" placeholder="None">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-3 col-form-label" for="first_name">Birthday</label>
                                                                            <div class="col-sm-3">
                                                                                <select class="form-control border-primary" id="dob_month" name="dob_month">
                                                                                    <option value="1"<?PHP if(USER_BOD_MONTH == 1){ HTML::print("selected=\selected\"", false); } ?>>January</option>
                                                                                    <option value="2"<?PHP if(USER_BOD_MONTH == 2){ HTML::print("selected=\selected\"", false); } ?>>February</option>
                                                                                    <option value="3"<?PHP if(USER_BOD_MONTH == 3){ HTML::print("selected=\selected\"", false); } ?>>March</option>
                                                                                    <option value="4"<?PHP if(USER_BOD_MONTH == 4){ HTML::print("selected=\selected\"", false); } ?>>April</option>
                                                                                    <option value="5"<?PHP if(USER_BOD_MONTH == 5){ HTML::print("selected=\selected\"", false); } ?>>May</option>
                                                                                    <option value="6"<?PHP if(USER_BOD_MONTH == 6){ HTML::print("selected=\selected\"", false); } ?>>June</option>
                                                                                    <option value="7"<?PHP if(USER_BOD_MONTH == 7){ HTML::print("selected=\selected\"", false); } ?>>July</option>
                                                                                    <option value="8"<?PHP if(USER_BOD_MONTH == 8){ HTML::print("selected=\selected\"", false); } ?>>August</option>
                                                                                    <option value="9"<?PHP if(USER_BOD_MONTH == 9){ HTML::print("selected=\selected\"", false); } ?>>September</option>
                                                                                    <option value="10<?PHP if(USER_BOD_MONTH == 10){ HTML::print("selected=\selected\"", false); } ?>">October</option>
                                                                                    <option value="11"<?PHP if(USER_BOD_MONTH == 11){ HTML::print("selected=\selected\"", false); } ?>>November</option>
                                                                                    <option value="12"<?PHP if(USER_BOD_MONTH == 12){ HTML::print("selected=\selected\"", false); } ?>>December</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <select class="form-control border-primary" id="dob_day" name="dob_day">
                                                                                    <?PHP
                                                                                    $FirstDay = 1;
                                                                                    $MaxDay = 31;
                                                                                    $CurrentCount = $FirstDay;

                                                                                    while(true)
                                                                                    {
                                                                                        if($CurrentCount > $MaxDay)
                                                                                        {
                                                                                            break;
                                                                                        }
                                                                                        if(USER_BOD_DAY == $CurrentCount)
                                                                                        {
                                                                                            HTML::print("<option value=\"" . $CurrentCount . "\"  selected=\"selected\">" . $CurrentCount . "</option>", false);
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            HTML::print("<option value=\"" . $CurrentCount . "\">" . $CurrentCount . "</option>", false);
                                                                                        }
                                                                                        $CurrentCount += 1;
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <select class="form-control border-primary" id="dob_year" name="dob_year">
                                                                                    <?PHP
                                                                                    $FirstYear = 1970;
                                                                                    $CurrentYear = (int)date('Y') - 13;
                                                                                    $CurrentCount = $FirstYear;

                                                                                    while(true)
                                                                                    {
                                                                                        if($CurrentCount > $CurrentYear)
                                                                                        {
                                                                                            break;
                                                                                        }
                                                                                        if(USER_BOD_YEAR == $CurrentCount)
                                                                                        {
                                                                                            HTML::print("<option value=\"" . $CurrentCount . "\" selected=\"selected\">" . $CurrentCount . "</option>", false);
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            HTML::print("<option value=\"" . $CurrentCount . "\">" . $CurrentCount . "</option>", false);
                                                                                        }
                                                                                        $CurrentCount += 1;
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-3">
                                                                                <input type="submit" class="btn btn-outline-primary" value="Save Changes">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </div>
                                                        <div class="tab-pane fade" id="user-profile-kh" role="tabpanel" aria-labelledby="user-profile-kh-tab">
                                                            <?PHP render_known_hosts($IntellivoidAccounts, $Account->Configuration->KnownHosts->KnownHosts); ?>
                                                        </div>

                                                        <div class="tab-pane fade" id="user-profile-kd" role="tabpanel" aria-labelledby="user-profile-kd-tab">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Browser</th>
                                                                        <th>Platform</th>
                                                                        <th>Version</th>
                                                                        <th>Last Seen</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
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

                                                                /** @var UserAgentRecord $device */
                                                                foreach($DeviceResults as $device)
                                                                {
                                                                    $platform_icon = "mdi mdi-desktop-mac";
                                                                    switch(strtolower($device->Platform))
                                                                    {
                                                                        case 'xbox one':
                                                                        case 'xbox':
                                                                            $platform_icon = 'mdi mdi-xbox';
                                                                            break;

                                                                        case 'windows phone':
                                                                            $platform_icon = 'mdi mdi-windows';
                                                                            break;

                                                                        case 'android':
                                                                            $platform_icon = 'mdi mdi-android';
                                                                            break;

                                                                        case 'linux':
                                                                        case 'linux-gnu':
                                                                        case 'x11':
                                                                            $platform_icon = 'mdi mdi-linux';
                                                                            break;

                                                                        case 'chrome os':
                                                                        case 'cros':
                                                                            $platform_icon = 'mdi mdi-laptop-chromebook';
                                                                            break;

                                                                        case 'blackBerry':
                                                                            $platform_icon = 'mdi mdi-blackberry';
                                                                            break;

                                                                        case 'playStation vita':
                                                                        case 'playStation':
                                                                            $platform_icon = 'mdi mdi-playstation';
                                                                            break;

                                                                        case 'iphone':
                                                                        case 'ipad':
                                                                            $platform_icon = 'mdi mdi-apple';
                                                                            break;

                                                                    }

                                                                    $browser_icon = "mdi mdi-web";
                                                                    switch(strtolower($device->Browser))
                                                                    {
                                                                        case 'firefox':
                                                                            $browser_icon = 'mdi mdi-firefox';
                                                                            break;

                                                                        case 'safari':
                                                                        case 'applewebkit':
                                                                            $browser_icon = 'mdi mdi-apple-safari';
                                                                            break;

                                                                        case 'edge':
                                                                            $browser_icon = 'mdi mdi-edge';
                                                                            break;

                                                                        case 'msie':
                                                                        case 'iemobile':
                                                                            $browser_icon = 'mdi mdi-internet-explorer';
                                                                            break;

                                                                        case 'lynx':
                                                                        case 'bingbot':
                                                                        case 'baiduspider':
                                                                        case 'googlebot':
                                                                        case 'yandexbot':
                                                                        case 'version':
                                                                        case 'wget':
                                                                        case 'curl':
                                                                            $browser_icon = 'mdi mdi-settings';
                                                                            break;

                                                                        case 'steam':
                                                                        case 'valve':
                                                                            $browser_icon = 'mdi mdi-steam';

                                                                        case 'chrome':
                                                                            $browser_icon = 'mdi mdi-google-chrome';
                                                                            break;
                                                                    }

                                                                    ?>
                                                                    <tr>
                                                                        <td><?PHP HTML::print($device->ID); ?></td>
                                                                        <td>
                                                                            <i class="<?PHP HTML::print($browser_icon); ?>"></i>
                                                                            <?PHP HTML::print($device->Browser); ?>
                                                                        </td>
                                                                        <td>
                                                                            <i class="<?PHP HTML::print($platform_icon); ?>"></i>
                                                                            <?PHP HTML::print($device->Platform); ?>
                                                                        </td>
                                                                        <td><?PHP HTML::print($device->Version); ?></td>
                                                                        <td><?PHP HTML::print(date("F j, Y, g:i a", $device->LastSeen)); ?></td>
                                                                    </tr>
                                                                    <?PHP
                                                                }
                                                                ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <h5 class="my-4">Who to follow</h5>
                                                    <div class="new-accounts">
                                                        <ul class="chats">
                                                            <li class="chat-persons">
                                                                <a href="#">
                                                              <span class="pro-pic">
                                                                <img src="../../../assets/images/faces/face2.jpg" alt="profile image"> </span>
                                                                    <div class="user">
                                                                        <p class="u-name">Marina Michel</p>
                                                                        <p class="u-designation">Business Development</p>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li class="chat-persons">
                                                                <a href="#">
                                  <span class="pro-pic">
                                    <img src="../../../assets/images/faces/face3.jpg" alt="profile image"> </span>
                                                                    <div class="user">
                                                                        <p class="u-name">Stella Johnson</p>
                                                                        <p class="u-designation">SEO Expert</p>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li class="chat-persons">
                                                                <a href="#">
                                  <span class="pro-pic">
                                    <img src="../../../assets/images/faces/face4.jpg" alt="profile image"> </span>
                                                                    <div class="user">
                                                                        <p class="u-name">Peter Joo</p>
                                                                        <p class="u-designation">UI/UX designer</p>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <h5 class="my-4">Pending</h5>
                                                    <div class="new-accounts">
                                                        <ul class="chats">
                                                            <li class="chat-persons">
                                                                <a href="#">
                                  <span class="pro-pic">
                                    <img src="../../../assets/images/faces/face5.jpg" alt="profile image"> </span>
                                                                    <div class="user">
                                                                        <p class="u-name">Marina Michel</p>
                                                                        <p class="u-designation">Business Development</p>
                                                                        <span class="d-flex align-items-center mt-2">
                                      <span class="btn btn-xs btn-rounded btn-outline-light mr-2">Buyer</span>
                                      <span class="btn btn-xs btn-rounded btn-outline-primary">Lead</span>
                                    </span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li class="chat-persons">
                                                                <a href="#">
                                  <span class="pro-pic">
                                    <img src="../../../assets/images/faces/face6.jpg" alt="profile image"> </span>
                                                                    <div class="user">
                                                                        <p class="u-name">Stella Johnson</p>
                                                                        <p class="u-designation">SEO Expert</p>
                                                                        <span class="d-flex align-items-center mt-2">
                                      <span class="btn btn-xs btn-rounded btn-outline-light mr-2">Buyer</span>
                                      <span class="btn btn-xs btn-rounded btn-outline-primary">Lead</span>
                                    </span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li class="chat-persons">
                                                                <a href="#">
                                  <span class="pro-pic">
                                    <img src="../../../assets/images/faces/face7.jpg" alt="profile image"> </span>
                                                                    <div class="user">
                                                                        <p class="u-name">Peter Joo</p>
                                                                        <p class="u-designation">UI/UX designer</p>
                                                                        <span class="d-flex align-items-center mt-2">
                                      <span class="btn btn-xs btn-rounded btn-outline-light mr-2">Buyer</span>
                                      <span class="btn btn-xs btn-rounded btn-outline-primary">Lead</span>
                                    </span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                        </ul>
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