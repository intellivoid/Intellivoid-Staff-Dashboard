<?php

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use IntellivoidAccounts\Abstracts\AccountStatus;
    use IntellivoidAccounts\Objects\Account;

    function render_status(Account $account)
    {
        $Status = "Unknown";
        $StatusColor = "primary";
        switch($account->Status)
        {
            case AccountStatus::Active:
                $Status = "Active";
                $StatusColor = "success";
                break;

            case AccountStatus::Suspended:
                $Status = "Suspended";
                $StatusColor = "danger";
                break;

            case AccountStatus::Limited:
                $Status = "Limited";
                $StatusColor = "warning";
                break;

            case AccountStatus::VerificationRequired:
                $Status = "Verification Required";
                $StatusColor = "warning";
                break;

            case AccountStatus::BlockedDueToGovernmentBackedAttack:
                $Status = "GBA Mode";
                $StatusColor = "danger";
                break;

            case AccountStatus::PasswordRecoveryMode:
                $Status = "Password Recovery Mode";
                $StatusColor = "warning";
                break;
        }
        ?>
            <div class="d-flex align-items-center text-center">
                <div class="btn-group ml-auto mr-auto mb-2">
                    <button type="button" class="btn btn-sm btn-<?PHP HTML::print($StatusColor); ?>"><?PHP HTML::print($Status); ?></button>
                    <button type="button" class="btn btn-sm btn-<?PHP HTML::print($StatusColor); ?> dropdown-toggle dropdown-toggle-split" id="status-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="status-dropdown">
                        <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_status', 'status' => 'active'), true); ?>">Active</a>
                        <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_status', 'status' => 'suspended'), true); ?>">Suspended</a>
                        <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_status', 'status' => 'limited'), true); ?>">Limited</a>
                        <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_status', 'status' => 'verification_required'), true); ?>">Verification Required</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#gbaDialog" href="#">GBA Mode</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#prmDialog" href="#">Password Recovery Mode</a>
                    </div>
                </div>
            </div>
        <?PHP
    }