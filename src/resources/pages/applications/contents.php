<?PHP
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
use IntellivoidAccounts\Abstracts\AccountStatus;
use IntellivoidAccounts\Abstracts\ApplicationStatus;
use IntellivoidAccounts\IntellivoidAccounts;
    use msqg\QueryBuilder;
use ZiProto\ZiProto;

Runtime::import('IntellivoidAccounts');
    HTML::importScript('db_render_helper');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $Results = get_results($IntellivoidAccounts->database, 500, 'applications', 'id',
        QueryBuilder::select('applications', ['id', 'public_app_id', 'name', 'status', 'flags', 'permissions'])
    );
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
                            <div class="col-lg-12 grid-margin">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Actions</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Applications</h4>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Application</th>
                                                    <th>ID</th>
                                                    <th>Public ID</th>
                                                    <th>Permissions</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?PHP
                                                    foreach($Results['results'] as $application)
                                                    {
                                                        $public_id = $application['public_app_id'];
                                                        $application['public_app_id'] = (strlen($application['public_app_id']) > 15) ? substr($application['public_app_id'], 0, 15) . '...' : $application['public_app_id'];
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <img src="<?PHP HTML::print(getApplicationUrl($public_id, 'tiny')); ?>" class="img-fluid" style="border-radius: 0;" alt="Profile Image">
                                                                <span class="pl-2"><?PHP HTML::print($application['name']); ?></span>

                                                            </td>
                                                            <td><?PHP HTML::print($application['id']); ?></td>
                                                            <td><?PHP HTML::print($application['public_app_id']); ?></td>
                                                            <td>
                                                                <?PHP
                                                                    $application['permissions'] = ZiProto::decode($application['permissions']);
                                                                    HTML::print("<i class=\"mdi mdi-account-card-details\"></i>", false);
                                                                    if(in_array(AccountRequestPermissions::ViewEmailAddress, $application['permissions']))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-email pl-1\"></i>", false);
                                                                    }
                                                                    if(in_array(AccountRequestPermissions::ReadPersonalInformation, $application['permissions']))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-account pl-1\"></i>", false);
                                                                    }
                                                                    if(in_array(AccountRequestPermissions::EditPersonalInformation, $application['permissions']))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-account-edit pl-1\"></i>", false);
                                                                    }
                                                                    if(in_array(AccountRequestPermissions::MakePurchases, $application['permissions']))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-shopping pl-1\"></i>", false);
                                                                    }
                                                                ?>

                                                            </td>
                                                            <td>
                                                                <?PHP
                                                                    switch((int)$application['status'])
                                                                    {
                                                                        case ApplicationStatus::Active:
                                                                            HTML::print("<label class=\"badge badge-success\">Active</label>", false);
                                                                            break;

                                                                        case ApplicationStatus::Disabled:
                                                                            HTML::print("<label class=\"badge badge-warning\">Disabled</label>", false);
                                                                            break;

                                                                        case ApplicationStatus::Suspended:
                                                                            HTML::print("<label class=\"badge badge-danger\">Suspended</label>", false);
                                                                            break;

                                                                        default:
                                                                            HTML::print("<label class=\"badge badge-primary\">Unknown</label>", false);
                                                                            break;
                                                                    }
                                                                ?>

                                                            </td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-xs btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuOutlineButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">
                                                                        <a class="dropdown-item" href="#">Manage Account</a>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <?PHP
                                                    }
                                                ?>

                                            </tbody>
                                        </table>
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