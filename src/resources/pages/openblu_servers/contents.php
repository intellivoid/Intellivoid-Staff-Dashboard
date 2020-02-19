<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use msqg\Abstracts\SortBy;
    use msqg\QueryBuilder;
    use OpenBlu\OpenBlu;

    Runtime::import('OpenBlu');
    HTML::importScript('process_search');
    HTML::importScript('db_render_helper');

    $OpenBlu = new OpenBlu();

    $where = null;
    $where_value = null;

    if(isset($_GET['filter']))
    {
        if($_GET['filter'] == 'country')
        {
            if(isset($_GET['value']))
            {
                $where = 'country';
                $where_value = $OpenBlu->database->real_escape_string($_GET['value']);
            }
        }

        if($_GET['filter'] == 'country_short')
        {
            if(isset($_GET['value']))
            {
                $where = 'country_short';
                $where_value = $OpenBlu->database->real_escape_string($_GET['value']);
            }
        }
    }

    $Results = get_results($OpenBlu->database, 5000, 'vpns', 'id',
        QueryBuilder::select(
                'vpns', ['id', 'public_id', 'ip_address', 'country', 'country_short', 'sessions', 'last_updated'],
                $where, $where_value, 'last_updated', SortBy::descending
        ),
    $where, $where_value);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff - OpenBlu Servers</title>
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
                                        <h4 class="card-title">Devices</h4>
                                        <div class="wrapper d-flex align-items-center">
                                            <button class="btn btn-transparent icon-btn arrow-disabled pl-2 pr-2 text-white text-small" data-toggle="modal" data-target="#filterDialog" type="button">
                                                <i class="mdi mdi-filter"></i>
                                            </button>
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
                                                            <th>ID</th>
                                                            <th>Public ID</th>
                                                            <th>IP Address</th>
                                                            <th>Country</th>
                                                            <th>Sessions</th>
                                                            <th>Last Updated</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                        foreach($Results['results'] as $server)
                                                        {
                                                            $public_id = $server['public_id'];
                                                            $server['public_id'] = (strlen($server['public_id']) > 15) ? substr($server['public_id'], 0, 15) . '...' : $server['public_id'];
                                                            $location_details =  $server['country'];
                                                            ?>
                                                            <tr>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP
                                                                    $CountryCode = strtolower($server['country_short']);
                                                                    HTML::print("<i class=\"flag-icon flag-icon-$CountryCode mr-1\" title=\"$CountryCode\"></i>", false);
                                                                    ?>
                                                                    <?PHP HTML::print($server['id']); ?>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($public_id); ?>"><?PHP HTML::print($server['public_id']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($server['ip_address']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($server['country']); ?> (<?PHP HTML::print($server['country_short']); ?>)</td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($server['sessions']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $server['last_updated'])); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_known_host', array('id' => $server['id']), true); ?>">View Details</a>
                                                                            <div class="dropdown-divider"></div>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('openblu_servers', $RedirectHref, true); ?>">
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
                                                                                    <a class="page-link" href="<?PHP DynamicalWeb::getRoute('openblu_servers', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('openblu_servers', $RedirectHref, true); ?>">
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
                    <?PHP HTML::importScript('filter_dialog'); ?>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>