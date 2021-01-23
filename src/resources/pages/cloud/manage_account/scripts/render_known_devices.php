<?php

    use DynamicalWeb\HTML;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\UserAgentRecord;

    function render_known_devices(IntellivoidAccounts $IntellivoidAccounts, array $devices)
    {
        if(count($devices) > 0)
        {
            ?>
            <div class="table-responsive">
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
                    /** @var UserAgentRecord $device */
                    foreach($devices as $device)
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
                                break;

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