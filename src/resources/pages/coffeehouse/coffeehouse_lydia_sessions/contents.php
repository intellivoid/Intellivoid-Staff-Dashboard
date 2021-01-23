<?PHP

    use CoffeeHouse\CoffeeHouse;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use msqg\Abstracts\SortBy;
    use msqg\QueryBuilder;

    Runtime::import('CoffeeHouse');
    HTML::importScript('db_render_helper');

    $CoffeeHouse = new CoffeeHouse();

    $where = null;
    $where_value = null;

    $Results = get_results($CoffeeHouse->getDatabase(), 5000, 'foreign_sessions', 'id',
        QueryBuilder::select(
                'foreign_sessions', ['id', 'session_id', 'language', 'available', 'messages', 'expires', 'last_updated'],
                $where, $where_value, 'last_updated', SortBy::descending
        ),
    $where, $where_value);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff - CoffeeHouse Lydia Chat Sessions</title>
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
                                        <h4 class="card-title">Lydia Chat Sessions</h4>
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
                                                            <th>ID</th>
                                                            <th>Session ID</th>
                                                            <th>Language</th>
                                                            <th>Available</th>
                                                            <th>Messages</th>
                                                            <th>Last Updated</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                        foreach($Results['results'] as $session)
                                                        {
                                                            $session_id = $session['session_id'];
                                                            $session['session_id'] = (strlen($session['session_id']) > 15) ? substr($session['session_id'], 0, 15) . '...' : $session['session_id'];

                                                            ?>
                                                            <tr>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP HTML::print($session['id']); ?>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($session_id); ?>"><?PHP HTML::print($session['session_id']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($session['language']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP
                                                                        if($session['available'])
                                                                        {
                                                                            if((int)time() > $session['expires'])
                                                                            {
                                                                                HTML::print("False");
                                                                            }
                                                                            else
                                                                            {
                                                                                HTML::print("True");
                                                                            }
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print("False");
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($session['messages']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $session['last_updated'])); ?></td>


                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_coffeehouse_lydia_session', array('id' => $session['id']), true); ?>">View Details</a>
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
                                                    $RedirectHref = $_GET;
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
                                                                            $RedirectHref['page'] = $Results['current_page'] - 1;
                                                                            ?>
                                                                            <li class="page-item">
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('coffeehouse_lydia_sessions', $RedirectHref, true); ?>">
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
                                                                                $RedirectHref['page'] = $current_count;
                                                                                ?>
                                                                                <li class="page-item">
                                                                                    <a class="page-link" href="<?PHP DynamicalWeb::getRoute('coffeehouse_lydia_sessions', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                            $RedirectHref['page'] = $Results['current_page'] + 1;
                                                                            ?>
                                                                            <li class="page-item">
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('coffeehouse_lydia_sessions', $RedirectHref, true); ?>">
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
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>