<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use Support\Abstracts\TicketStatus;
    use Support\Objects\SupportTicket;
    use Support\Support;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'set_status')
        {
            if(isset($_GET['status']))
            {
                set_status();
            }
        }
    }

    function set_status()
    {
        /** @var Support $SupportManager */
        $SupportManager = DynamicalWeb::getMemoryObject('support_manager');

        /** @var SupportTicket $SupportTicket */
        $SupportTicket = DynamicalWeb::getMemoryObject('support_ticket');

        switch(strtolower($_GET['status']))
        {
            case 'opened':
                $SupportTicket->TicketStatus = TicketStatus::Opened;
                break;

            case 'in_progress':
                $SupportTicket->TicketStatus = TicketStatus::InProgress;
                break;

            case 'unable_to_resolve':
                $SupportTicket->TicketStatus = TicketStatus::UnableToResolve;
                break;

            case 'resolved':
                $SupportTicket->TicketStatus = TicketStatus::Resolved;
                break;

            default:
                Actions::redirect(
                    DynamicalWeb::getRoute('view_support_ticket', array('id' => $_GET['id'], 'callback' => '101'))
                );
                break;
        }

        try
        {
            $SupportManager->getTicketManager()->updateSupportTicket($SupportTicket);
        }
        catch(Exception $exception)
        {
            Actions::redirect(
                DynamicalWeb::getRoute('view_support_ticket', array('id' => $_GET['id'], 'callback' => '100'))
            );
        }

        Actions::redirect(
            DynamicalWeb::getRoute('view_support_ticket', array('id' => $_GET['id']))
        );
    }