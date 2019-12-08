<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use Support\Abstracts\TicketStatus;
use Support\Exceptions\InvalidTicketNotesException;
use Support\Objects\SupportTicket;
    use Support\Support;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_notes')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                if(isset($_POST['admin_notes']))
                {
                    update_notes();
                }
            }
        }
    }

    function update_notes()
    {
        /** @var Support $SupportManager */
        $SupportManager = DynamicalWeb::getMemoryObject('support_manager');

        /** @var SupportTicket $SupportTicket */
        $SupportTicket = DynamicalWeb::getMemoryObject('support_ticket');

        $SupportTicket->TicketNotes = $_POST['admin_notes'];

        try
        {
            $SupportManager->getTicketManager()->updateSupportTicket($SupportTicket);
        }
        catch(InvalidTicketNotesException $exception)
        {
            Actions::redirect(
                DynamicalWeb::getRoute('view_support_ticket', array('id' => $_GET['id'], 'callback' => '102'))
            );
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