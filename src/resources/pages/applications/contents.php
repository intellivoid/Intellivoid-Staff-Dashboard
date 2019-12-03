<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
use IntellivoidAccounts\Abstracts\ApplicationFlags;
use IntellivoidAccounts\Abstracts\ApplicationStatus;
    use IntellivoidAccounts\IntellivoidAccounts;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    Runtime::import('IntellivoidAccounts');
    HTML::importScript('process_search');
    HTML::importScript('db_render_helper');

    $IntellivoidAccounts = new IntellivoidAccounts();


    $where = null;
    $where_value = null;

    if(isset($_GET['filter']))
    {
        if($_GET['filter'] == 'account_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'account_id';
                $where_value = (int)$_GET['value'];
            }
        }
    }

    $Results = get_results($IntellivoidAccounts->database, 100, 'applications', 'id',
        QueryBuilder::select('applications',
            ['id', 'public_app_id', 'name', 'status', 'flags', 'permissions', 'account_id'],  $where, $where_value
        ),
        $where, $where_value
    );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Manage Applications</title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <?PHP HTML::importScript('callbacks'); ?>
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-header header-sm d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Applications</h4>
                                        <div class="wrapper d-flex align-items-center">
                                            <button class="btn btn-transparent icon-btn arrow-disabled pl-2 pr-2 text-white text-small" data-toggle="modal" data-target="#searchDialog" type="button">
                                                <i class="mdi mdi-magnify"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>ID</th>
                                                    <th>Public ID</th>
                                                    <th>Account ID</th>
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
                                                    $flags = ZiProto::decode($application['flags']);
                                                    $application['public_app_id'] = (strlen($application['public_app_id']) > 15) ? substr($application['public_app_id'], 0, 15) . '...' : $application['public_app_id'];
                                                    ?>
                                                    <tr>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <img src="<?PHP HTML::print(getApplicationUrl($public_id, 'tiny')); ?>" class="img-fluid rounded-circle" style="border-radius: 0;" alt="Profile Image">
                                                            <span class="pl-2">
                                                                <?PHP HTML::print($application['name']); ?>
                                                                <?PHP
                                                                if(in_array(ApplicationFlags::Official, $flags))
                                                                {
                                                                    HTML::print("<i class=\"mdi mdi-verified text-primary pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is an official Intellivoid Application/Service\"></i>", false);

                                                                }
                                                                elseif(in_array(ApplicationFlags::Verified, $flags))
                                                                {
                                                                    HTML::print("<i class=\"mdi mdi-verified text-success pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is verified & trusted\"></i>", false);
                                                                }
                                                                elseif(in_array(ApplicationFlags::Untrusted, $flags))
                                                                {
                                                                    HTML::print("<i class=\"mdi mdi-alert text-danger pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is untrusted and unsafe\"></i>", false);
                                                                }
                                                                ?>
                                                            </span>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($application['id']); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($application['public_app_id']); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($application['account_id']); ?></span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-account text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <h6 class="mb-0">Account ID <?PHP HTML::print($application['account_id']); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $application['account_id']), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-pencil"></i>
                                                                        </a>
                                                                        <a href="<?PHP DynamicalWeb::getRoute('applications', array('filter' => 'account_id', 'value' => $application['account_id']), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
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
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
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
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Actions</a>
                                                                <div class="dropdown-menu" >
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $application['id']), true); ?>">Manage Application</a>
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
                                        <?PHP
                                        if($Results['total_pages'] > 1)
                                        {
                                            ?>
                                            <div class="wrapper mt-4">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <div class="p-2 my-flex-item">
                                                        <nav>
                                                            <ul class="pagination flat pagination-success flex-wrap">
                                                                <?PHP
                                                                if($Results['current_page'] == 1)
                                                                {
                                                                    ?>
                                                                    <li class="page-item">
                                                                        <a class="page-link disabled" disabled>
                                                                            <i class="mdi mdi-chevron-left"></i>
                                                                        </a>
                                                                    </li>

                                                                    <?PHP
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                    <li class="page-item">
                                                                        <a class="page-link" href="<?PHP DynamicalWeb::getRoute('applications', array('page' => $Results['current_page'] -1), true); ?>">
                                                                            <i class="mdi mdi-chevron-left"></i>
                                                                        </a>
                                                                    </li>
                                                                    <?PHP
                                                                }

                                                                $current_count = 1;
                                                                while(True)
                                                                {
                                                                    if($Results['current_page'] == $current_count)
                                                                    {
                                                                        ?>
                                                                        <li class="page-item active">
                                                                            <a class="page-link disabled" disabled><?PHP HTML::print($current_count); ?></a>
                                                                        </li>
                                                                        <?PHP
                                                                    }
                                                                    else
                                                                    {
                                                                        ?>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="<?PHP DynamicalWeb::getRoute('applications', array('page' => $current_count), true); ?>"><?PHP HTML::print($current_count); ?></a>
                                                                        </li>
                                                                        <?PHP
                                                                    }

                                                                    if($Results['total_pages'] == $current_count)
                                                                    {
                                                                        break;
                                                                    }

                                                                    $current_count += 1;
                                                                }

                                                                if($Results['current_page'] == $Results['total_pages'])
                                                                {
                                                                    ?>
                                                                    <li class="page-item">
                                                                        <a class="page-link disabled" disabled>
                                                                            <i class="mdi mdi-chevron-right"></i>
                                                                        </a>
                                                                    </li>

                                                                    <?PHP
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                    <li class="page-item">
                                                                        <a class="page-link" href="<?PHP DynamicalWeb::getRoute('applications', array('page' => $Results['current_page'] +1), true); ?>">
                                                                            <i class="mdi mdi-chevron-right"></i>
                                                                        </a>
                                                                    </li>
                                                                    <?PHP
                                                                }
                                                                ?>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
                                            </div>
                                            <?PHP
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?PHP HTML::importScript('search_dialog'); ?>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>