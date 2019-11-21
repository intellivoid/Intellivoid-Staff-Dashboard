<?PHP

    use DynamicalWeb\HTML;
    use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;

    function render_known_hosts(IntellivoidAccounts $IntellivoidAccounts, array $known_hosts)
    {
        if(count($known_hosts) > 0)
        {
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>IP</th>
                        <th>Country</th>
                        <th>Last Used</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                <tbody>
                    <?PHP
                        foreach($known_hosts as $host_id)
                        {
                            try
                            {
                                $KnownHost = $IntellivoidAccounts->getKnownHostsManager()->getHost(KnownHostsSearchMethod::byId, (int)$host_id);
                            }
                            catch(Exception $exception)
                            {
                                continue;
                            }

                            if($KnownHost->LocationData->CountryCode == null)
                            {
                                $country = "Unknown";
                                $flag_icon = "mdi mdi-map-marker-off";
                            }
                            else
                            {
                                $country = $KnownHost->LocationData->CountryName;
                                $flag_icon = "flag-icon pr-2 flag-icon-" . strtolower($KnownHost->LocationData->CountryCode);
                            }

                            ?>
                            <tr>
                                <td>
                                    <i class="<?PHP HTML::print($flag_icon); ?>" title="<?PHP HTML::print($flag_icon); ?>"></i>
                                    <?PHP HTML::print($KnownHost->ID); ?>
                                </td>
                                <td><?PHP HTML::print($KnownHost->IpAddress); ?></td>
                                <td><?PHP HTML::print($country); ?></td>
                                <td><?PHP HTML::print(date("F j, Y, g:i a", $KnownHost->LastUsed)); ?></td>
                                <td>
                                    <button class="btn btn-xs btn-outline-primary">
                                        <i class="mdi mdi-database-search"></i>
                                    </button>
                                </td>
                            </tr>
                            <?PHP
                        }
                    ?>
                </tbody>
            </table>
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

    }