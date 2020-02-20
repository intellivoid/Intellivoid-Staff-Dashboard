<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\AccountStatus;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Account\Configuration;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    Runtime::import('IntellivoidAccounts');
    HTML::importScript('process_search');
    HTML::importScript('db_render_helper');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $Results = get_results($IntellivoidAccounts->database, 100, 'users', 'id',
        QueryBuilder::select('users', ['id', 'public_id', 'username', 'email', 'status', 'creation_date', 'configuration'])
    );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Manage Accounts</title>
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
                                        <h4 class="card-title">Accounts</h4>
                                        <div class="wrapper d-flex align-items-center">
                                            <button class="btn btn-transparent icon-btn arrow-disabled pl-2 pr-2 text-white text-small" data-toggle="modal" data-target="#searchDialog" type="button">
                                                <i class="mdi mdi-magnify"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?PHP
                                            if(count($Results['results']) > 0)
                                            {
                                                ?>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Username</th>
                                                            <th>ID</th>
                                                            <th>Public ID</th>
                                                            <th>Email</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                        foreach($Results['results'] as $account)
                                                        {
                                                            $configuration = Configuration::fromArray(ZiProto::decode($account['configuration']));
                                                            $public_id = $account['public_id'];
                                                            $account['public_id'] = (strlen($account['public_id']) > 15) ? substr($account['public_id'], 0, 15) . '...' : $account['public_id'];
                                                            $account['username'] = (strlen($account['username']) > 15) ? substr($account['username'], 0, 15) . '...' : $account['username'];
                                                            ?>
                                                            <tr>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <img src="<?PHP HTML::print(getAvatarUrl($public_id, 'tiny')); ?>" class="img-fluid rounded-circle" style="border-radius: 0;" alt="Profile Image">
                                                                    <span class="pl-2">
                                                                            <?PHP
                                                                            HTML::print($account['username']);
                                                                            if($configuration->Roles->has_role("ADMINISTRATOR"))
                                                                            {
                                                                                HTML::print("<i class=\"mdi mdi-shield pl-1\"></i>", false);
                                                                            }
                                                                            if($configuration->Roles->has_role("MODERATOR"))
                                                                            {
                                                                                HTML::print("<i class=\"mdi mdi-security pl-1\"></i>", false);
                                                                            }
                                                                            if($configuration->Roles->has_role("SUPPORT"))
                                                                            {
                                                                                HTML::print("<i class=\"mdi mdi-lifebuoy pl-1\"></i>", false);
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($account['id']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($account['public_id']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($account['email']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP
                                                                    switch((int)$account['status'])
                                                                    {
                                                                        case AccountStatus::Active:
                                                                            HTML::print("<label class=\"badge badge-success\">Active</label>", false);
                                                                            break;

                                                                        case AccountStatus::Suspended:
                                                                            HTML::print("<label class=\"badge badge-danger\">Suspended</label>", false);
                                                                            break;

                                                                        case AccountStatus::Limited:
                                                                            HTML::print("<label class=\"badge badge-warning\">Limited</label>", false);
                                                                            break;

                                                                        case AccountStatus::VerificationRequired:
                                                                            HTML::print("<label class=\"badge badge-primary\">Verification Required</label>", false);
                                                                            break;

                                                                        case AccountStatus::BlockedDueToGovernmentBackedAttack:
                                                                            HTML::print("<label class=\"badge badge-danger\">GBA Mode</label>", false);
                                                                            break;

                                                                        case AccountStatus::PasswordRecoveryMode:
                                                                            HTML::print("<label class=\"badge badge-warning\">PR Mode</label>", false);
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
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $account['id']), true); ?>">Manage Account</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $account['id'], 'action' => 'export_data'), true); ?>">Export Data</a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('accounts', array('page' => $Results['current_page'] -1), true); ?>">
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
                                                                                    <a class="page-link" href="<?PHP DynamicalWeb::getRoute('accounts', array('page' => $current_count), true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('accounts', array('page' => $Results['current_page'] +1), true); ?>">
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
                                                <?PHP
                                            }
                                            else
                                            {
                                                ?>
                                                <div class="wrapper mt-4">
                                                    <div class="d-flex flex-column justify-content-center align-items-center" style="height:50vh;">
                                                        <div class="p-2 my-flex-item">
                                                            <h4>No Items</h4>
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
    </body>
</html>