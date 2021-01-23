<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
    use udp\Exceptions\FileUploadException;
    use udp\Exceptions\ImageTooSmallException;
    use udp\Exceptions\InvalidImageException;
    use udp\Exceptions\SystemException;
    use udp\Exceptions\UnsupportedFileTypeException;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_logo')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                try
                {
                    update_logo();

                }
                catch(Exception $e)
                {
                    Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                        array('id' => $_GET['id'], 'callback' => '113')
                    ));
                }
            }
        }
    }

    function update_logo()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");


        try
        {
            $file = $IntellivoidAccounts->getAppUdp()->getTemporaryFileManager()->accept_upload();
        }
        catch (FileUploadException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '110'))
            );
        }
        catch (SystemException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '111'))
            );
        }
        catch (UnsupportedFileTypeException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '112'))
            );
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '113'))
            );

        }


        try
        {
            $IntellivoidAccounts->getAppUdp()->getProfilePictureManager()->apply_avatar($file, $Application->PublicAppId);
        }
        catch (ImageTooSmallException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '114'))
            );
        }
        catch (InvalidImageException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '115'))
            );
        }
        catch (UnsupportedFileTypeException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '112'))
            );
        }

        Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application', array(
            'id' => $_GET['id'],
            'callback' => '116'
        )));
    }