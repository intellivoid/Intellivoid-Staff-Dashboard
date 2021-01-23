<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
use IntellivoidAccounts\Abstracts\AuditEventType;
use IntellivoidAccounts\Abstracts\LoginStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\LoginRecordMultiSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Account;
use IntellivoidAccounts\Objects\AuditRecord;
use IntellivoidAccounts\Objects\KnownHost;
    use IntellivoidAccounts\Objects\UserLoginRecord;

    function render_audit_logs(IntellivoidAccounts $IntellivoidAccounts, Account $account)
    {
        ?>
        <div class="wrapper mt-4">
            <?PHP
            $AuditRecords = $IntellivoidAccounts->getAuditLogManager()->getNewRecords($_GET['id'], 100);

            if(count($AuditRecords) > 0)
            {
                HTML::print("<ul class=\"bullet-line-list ml-4 pb-3\">", false);
                foreach($AuditRecords as $AuditRecord)
                {
                    $AuditRecordObject = AuditRecord::fromArray($AuditRecord);
                    $EventText = null;
                    $EventIcon = null;

                    switch($AuditRecordObject->EventType)
                    {
                        case AuditEventType::NewLoginDetected:
                            $EventText = "New login detected";
                            $EventIcon = "mdi mdi-shield text-success";
                            break;

                        case AuditEventType::PasswordUpdated:
                            $EventText = "Password Updated";
                            $EventIcon = "mdi mdi-key-change text-success";
                            break;

                        case AuditEventType::PersonalInformationUpdated:
                            $EventText = "Personal Information Updated";
                            $EventIcon = "mdi mdi-account text-success";
                            break;

                        case AuditEventType::EmailUpdated:
                            $EventText = "Email Changed";
                            $EventIcon = "mdi mdi-email text-success";
                            break;

                        case AuditEventType::MobileVerificationEnabled:
                            $EventText = "Mobile Verification Enabled";
                            $EventIcon = "mdi mdi-cellphone-iphone text-success";
                            break;

                        case AuditEventType::MobileVerificationDisabled:
                            $EventText = "Mobile Verification Disabled";
                            $EventIcon = "mdi mdi-cellphone-iphone text-danger";
                            break;

                        case AuditEventType::RecoveryCodesEnabled:
                            $EventText = "Recovery Codes Enabled";
                            $EventIcon = "mdi mdi-refresh text-success";
                            break;

                        case AuditEventType::RecoveryCodesDisabled:
                            $EventText = "Recovery Codes Disabled";
                            $EventIcon = "mdi mdi-refresh text-danger";
                            break;

                        case AuditEventType::TelegramVerificationEnabled:
                            $EventText = "Telegram Verification Enabled";
                            $EventIcon = "mdi mdi-telegram text-success";
                            break;

                        case AuditEventType::TelegramVerificationDisabled:
                            $EventText = "Telegram Verification Disabled";
                            $EventIcon = "mdi mdi-telegram text-danger";
                            break;

                        case AuditEventType::ApplicationCreated:
                            $EventText = "Application Created";
                            $EventIcon = "mdi mdi-console text-success";
                            break;

                        case AuditEventType::NewLoginLocationDetected:
                            $EventText = "New Login Location Detected";
                            $EventIcon = "mdi mdi-map-marker text-success";
                            break;

                        default:
                            $EventText = "Unknown";
                            $EventIcon = "mdi mdi-help text-muted";
                            break;
                    }
                    $Timestamp = gmdate("j/m/y g:i a", $AuditRecordObject->Timestamp);

                    ?>
                    <li>
                        <div class="d-flex align-items-center justify-content-between pb-2">
                            <div class="d-flex">
                                <div class="ml-3">
                                    <p class="mb-0 pb-2"><?PHP HTML::print($EventText); ?></p>
                                    <i class="<?PHP HTML::print($EventIcon); ?>"></i>
                                    <small class="text-muted"> <?PHP HTML::print($Timestamp); ?> </small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?PHP
                }
                HTML::print("</ul>", false);
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
        <button class="btn btn-block btn-xs btn-outline-primary" onclick="location.href='<?PHP DynamicalWeb::getRoute('cloud/audit_logs', array('filter' => 'account_id', 'value' => $account->ID), true) ?>';">View More</button>
        <?PHP

    }