<?php

    use DynamicalWeb\DynamicalWeb;

    function render_roles(\IntellivoidAccounts\Objects\Account $account)
    {
        ?>
        <div class="d-flex align-items-center">
            <div class="wrapper d-flex align-items-center media-info">
                <i class="mdi mdi-shield icon-md"></i>
                <p class="card-title ml-3 mb-0">Administrator</p>
            </div>
            <div class="wrapper ml-auto action-bar">
                <?PHP
                    if($account->Configuration->Roles->has_role('ADMINISTRATOR'))
                    {
                        ?>
                        <a class="text-white" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'revoke_permission', 'permission' => 'administrator'), true); ?>">
                            <i class="mdi mdi-close mr-3"></i>
                        </a>
                        <?PHP
                    }
                    else
                    {
                        ?>
                        <a class="text-white" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_permission', 'permission' => 'administrator'), true); ?>">
                            <i class="mdi mdi-check mr-3"></i>
                        </a>
                        <?PHP
                    }
                ?>
            </div>
        </div>
        <div class="d-flex align-items-center border-top mt-3">
            <div class="wrapper d-flex align-items-center media-info pt-3">
                <i class="mdi mdi-security icon-md"></i>
                <p class="card-title ml-3 mb-0">Moderator</p>
            </div>
            <div class="wrapper ml-auto action-bar pt-3">
                <?PHP
                    if($account->Configuration->Roles->has_role('MODERATOR'))
                    {
                        ?>
                        <a class="text-white" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'revoke_permission', 'permission' => 'moderator'), true); ?>">
                            <i class="mdi mdi-close mr-3"></i>
                        </a>
                        <?PHP
                    }
                    else
                    {
                        ?>
                        <a class="text-white" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_permission', 'permission' => 'moderator'), true); ?>">
                            <i class="mdi mdi-check mr-3"></i>
                        </a>
                        <?PHP
                    }
                ?>
            </div>
        </div>
        <div class="d-flex align-items-center border-top mt-3">
            <div class="wrapper d-flex align-items-center media-info pt-3">
                <i class="mdi mdi-lifebuoy icon-md"></i>
                <p class="card-title ml-3 mb-0">Support</p>
            </div>
            <div class="wrapper ml-auto action-bar pt-3">
                <?PHP
                    if($account->Configuration->Roles->has_role('SUPPORT'))
                    {
                        ?>
                        <a class="text-white" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'revoke_permission', 'permission' => 'support'), true); ?>">
                            <i class="mdi mdi-close mr-3"></i>
                        </a>
                        <?PHP
                    }
                    else
                    {
                        ?>
                        <a class="text-white" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_permission', 'permission' => 'support'), true); ?>">
                            <i class="mdi mdi-check mr-3"></i>
                        </a>
                        <?PHP
                    }
                ?>
            </div>
        </div>
        <?PHP
    }