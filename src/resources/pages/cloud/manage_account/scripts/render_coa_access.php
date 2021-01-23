<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
    use IntellivoidAccounts\Abstracts\ApplicationAccessStatus;
use IntellivoidAccounts\Abstracts\ApplicationFlags;
use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Account;
    use IntellivoidAccounts\Objects\ApplicationAccess;
use IntellivoidAccounts\Objects\COA\Application;

    function render_coa_access(IntellivoidAccounts $IntellivoidAccounts, Account $account)
    {
        $ApplicationAccessRecords = $IntellivoidAccounts->getCrossOverAuthenticationManager()->getApplicationAccessManager()->searchRecordsByAccount(WEB_ACCOUNT_ID);
        $TotalAccessCount = 0;
        $Applications = array();

        if(count($ApplicationAccessRecords) > 0)
        {
            foreach($ApplicationAccessRecords as $record)
            {
                if($record['status'] == ApplicationAccessStatus::Authorized)
                {
                    $TotalAccessCount += 1;

                    try
                    {
                        $Application = $IntellivoidAccounts->getApplicationManager()->getApplication(ApplicationSearchMethod::byId, $record['application_id']);
                        $Applications[$record['application_id']] = $Application;
                    }
                    catch (Exception $e)
                    {
                        unset($e);
                        $TotalAccessCount -= 1;
                        continue;
                    }
                }
            }
        }


        if($TotalAccessCount > 0)
        {
            ?>
            <div class="accordion" id="apps-accordion" role="tablist">
                <?PHP
                foreach($ApplicationAccessRecords as $access_record)
                {
                    $ApplicationAccess = ApplicationAccess::fromArray($access_record);

                    if($ApplicationAccess->Status == ApplicationAccessStatus::Authorized)
                    {
                        if(isset($applications[$ApplicationAccess->ApplicationID]))
                        {
                            /** @var Application $Application */
                            $Application = $Applications[$ApplicationAccess->ApplicationID];
                        }
                        else
                        {
                            continue;
                        }
                        ?>
                        <div class="card accordion-minimal" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-top-right-radius: 0; border-top-left-radius: 0; border-bottom 0;">
                            <div class="card-header" role="tab" id="heading-<?PHP HTML::print($Application->PublicAppId); ?>">
                                <a class="mb-0 d-flex collapsed" data-toggle="collapse" href="#collapse-<?PHP HTML::print($Application->PublicAppId); ?>" aria-expanded="false" aria-controls="collapse-<?PHP HTML::print($Application->PublicAppId); ?>">
                                    <img class="img-xs rounded-circle mt-2" src="<?PHP HTML::print(getApplicationUrl($Application->PublicAppId, 'small')); ?>" alt="profile image">
                                    <div class="ml-3">
                                        <h6 class="mb-0">
                                            <?PHP HTML::print($Application->Name); ?>
                                            <?PHP
                                            if(in_array(ApplicationFlags::Official, $Application->Flags))
                                            {
                                                HTML::print("<i class=\"mdi mdi-verified text-primary pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is an official Intellivoid Application/Service\"></i>", false);

                                            }
                                            elseif(in_array(ApplicationFlags::Verified, $Application->Flags))
                                            {
                                                HTML::print("<i class=\"mdi mdi-verified text-success pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is verified & trusted\"></i>", false);
                                            }
                                            elseif(in_array(ApplicationFlags::Untrusted, $Application->Flags))
                                            {
                                                HTML::print("<i class=\"mdi mdi-alert text-danger pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is untrusted and unsafe\"></i>", false);
                                            }
                                            ?>
                                        </h6>
                                        <small class="text-muted"><?PHP HTML::print(str_ireplace('%s', gmdate("j/m/y g:i a", $ApplicationAccess->LastAuthenticatedTimestamp), 'Last Authenticated: %s')); ?></small>
                                    </div>
                                    <div class="ml-auto mr-3 mt-auto mb-auto">

                                        <i class="mdi mdi-account-card-details"></i>
                                        <?PHP
                                        if(in_array(AccountRequestPermissions::ViewEmailAddress ,$ApplicationAccess->Permissions))
                                        {
                                            HTML::print("<i class=\"mdi mdi-email\"></i>", false);
                                        }
                                        if(in_array(AccountRequestPermissions::ReadPersonalInformation ,$ApplicationAccess->Permissions))
                                        {
                                            HTML::print("<i class=\"mdi mdi-account\"></i>", false);
                                        }
                                        if(in_array(AccountRequestPermissions::EditPersonalInformation ,$ApplicationAccess->Permissions))
                                        {
                                            HTML::print("<i class=\"mdi mdi-account-edit\"></i>", false);
                                        }
                                        if(in_array(AccountRequestPermissions::MakePurchases ,$ApplicationAccess->Permissions))
                                        {
                                            HTML::print("<i class=\"mdi mdi-shopping\"></i>", false);
                                        }
                                        if(in_array(AccountRequestPermissions::TelegramNotifications ,$ApplicationAccess->Permissions))
                                        {
                                            HTML::print("<i class=\"mdi mdi-telegram\"></i>", false);
                                        }
                                        ?>

                                    </div>
                                </a>
                            </div>
                            <div id="collapse-<?PHP HTML::print($Application->PublicAppId); ?>" class="collapse" role="tabpanel" aria-labelledby="heading-<?PHP HTML::print($Application->PublicAppId); ?>" data-parent="#apps-accordion">
                                <div class="card-body">
                                    <div class="ml-2 mr-2 row grid-margin d-flex mb-3">
                                        <div class="col-lg-9 mb-2">
                                            <p><?PHP HTML::print(str_ireplace("%s", $Application->Name, "%s has access to")); ?></p>
                                            <div class="d-flex ml-2 align-items-center py-1 pb-2">
                                                <i class="mdi mdi-account-card-details mdi-18px"></i>
                                                <p class="mb-0 ml-3">Username and avatar</p>
                                            </div>
                                            <?PHP
                                            if(in_array(AccountRequestPermissions::ViewEmailAddress, $ApplicationAccess->Permissions))
                                            {
                                                ?>
                                                <div class="d-flex ml-2 align-items-center py-1 pb-2">
                                                    <i class="mdi mdi-email mdi-18px"></i>
                                                    <p class="mb-0 ml-3">View Email Address</p>
                                                </div>
                                                <?PHP
                                            }
                                            if(in_array(AccountRequestPermissions::ReadPersonalInformation, $ApplicationAccess->Permissions))
                                            {
                                                ?>
                                                <div class="d-flex ml-2 align-items-center py-1 pb-2">
                                                    <i class="mdi mdi-account mdi-18px"></i>
                                                    <p class="mb-0 ml-3">View personal information</p>
                                                </div>
                                                <?PHP
                                            }
                                            if(in_array(AccountRequestPermissions::EditPersonalInformation, $ApplicationAccess->Permissions))
                                            {
                                                ?>
                                                <div class="d-flex ml-2 align-items-center py-1 pb-2">
                                                    <i class="mdi mdi-account-edit mdi-18px"></i>
                                                    <p class="mb-0 ml-3">Edit personal information</p>
                                                </div>
                                                <?PHP
                                            }
                                            if(in_array(AccountRequestPermissions::MakePurchases, $ApplicationAccess->Permissions))
                                            {
                                                ?>
                                                <div class="d-flex ml-2 align-items-center py-1 pb-2">
                                                    <i class="mdi mdi-shopping mdi-18px"></i>
                                                    <p class="mb-0 ml-3">Make purchases on users behalf</p>
                                                </div>
                                                <?PHP
                                            }
                                            if(in_array(AccountRequestPermissions::TelegramNotifications, $ApplicationAccess->Permissions))
                                            {
                                                ?>
                                                <div class="d-flex ml-2 align-items-center py-1 pb-2">
                                                    <i class="mdi mdi-telegram mdi-18px"></i>
                                                    <p class="mb-0 ml-3">Send notifications to user on Telegram</p>
                                                </div>
                                                <?PHP
                                            }
                                            ?>

                                        </div>
                                        <div class="col-lg-3 mt-auto mb-2">
                                            <button class="btn btn-sm btn-outline-danger" onclick="location.href='<?PHP DynamicalWeb::getRoute('cloud/manage_account', array('id' => $account->ID, 'action' => 'revoke_access', 'access_id' => $ApplicationAccess->PublicID), true); ?>';">Revoke Access</button>
                                        </div>
                                    </div>
                                    <div class="border-top"></div>
                                    <div class="ml-2 mr-2 mt-4">
                                        <a class="btn btn-xs btn-primary mr-2" href="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $Application->ID), true); ?>">View Application</a>
                                        <a class="btn btn-xs btn-primary mr-2" href="#">View Application Access</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?PHP
                    }
                }
                ?>
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
