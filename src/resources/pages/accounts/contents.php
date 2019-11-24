<?PHP

use DynamicalWeb\DynamicalWeb;
use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\AccountStatus;
    use IntellivoidAccounts\IntellivoidAccounts;
    use msqg\QueryBuilder;

    Runtime::import('IntellivoidAccounts');
    HTML::importScript('db_render_helper');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $Results = get_results($IntellivoidAccounts->database, 500, 'users', 'id',
        QueryBuilder::select('users', ['id', 'public_id', 'username', 'email', 'status', 'creation_date'])
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
                                        <h4 class="card-title">Accounts</h4>
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
                                                            $public_id = $account['public_id'];
                                                            $account['public_id'] = (strlen($account['public_id']) > 15) ? substr($account['public_id'], 0, 15) . '...' : $account['public_id'];
                                                            $account['username'] = (strlen($account['username']) > 15) ? substr($account['username'], 0, 15) . '...' : $account['username'];
                                                            ?>
                                                                <tr>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <img src="<?PHP HTML::print(getAvatarUrl($public_id, 'tiny')); ?>" class="img-fluid" style="border-radius: 0;" alt="Profile Image">
                                                                        <span class="pl-2"><?PHP HTML::print($account['username']); ?></span>

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

                                                                            default:
                                                                                HTML::print("<label class=\"badge badge-\">Limited</label>", false);
                                                                                break;
                                                                        }
                                                                        ?>

                                                                    </td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <div class="dropdown">
                                                                            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Actions</a>
                                                                            <div class="dropdown-menu" >
                                                                                <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $account['id']), true); ?>">Manage Account</a>
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