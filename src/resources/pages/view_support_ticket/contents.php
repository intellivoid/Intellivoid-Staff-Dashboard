<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use Support\Abstracts\SupportTicketSearchMethod;
    use Support\Abstracts\TicketStatus;
    use Support\Exceptions\SupportTicketNotFoundException;
    use Support\Support;

    Runtime::import('TicketSupport');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('support_tickets'));
    }

    $SupportManager = new Support();

    try
    {
        $SupportTicket = $SupportManager->getTicketManager()->getSupportTicket(
                SupportTicketSearchMethod::byId, $_GET['id']
        );
    }
    catch (SupportTicketNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('support_tickets', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('support_tickets', array('callback' => '105')));
    }

    DynamicalWeb::setMemoryObject('support_manager', $SupportManager);
    DynamicalWeb::setMemoryObject('support_ticket', $SupportTicket);

    HTML::importScript('set_status');
    HTML::importScript('update_notes');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Manage Support Ticket (<?PHP HTML::print($SupportTicket->TicketNumber); ?>)</title>
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
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header header-sm d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Manage Support Ticket</h4>
                                        <div class="wrapper d-flex align-items-center">
                                            <div class="dropdown">
                                                <?PHP
                                                    $btn_color = "primary";
                                                    $btn_text = "Unknown";

                                                    switch($SupportTicket->TicketStatus)
                                                    {
                                                        case TicketStatus::Opened:
                                                            $btn_color = "outline-primary";
                                                            $btn_text = "Opened";
                                                            break;

                                                        case TicketStatus::InProgress:
                                                            $btn_color = "primary";
                                                            $btn_text = "In Progress";
                                                            break;

                                                        case TicketStatus::UnableToResolve:
                                                            $btn_color = "danger";
                                                            $btn_text = "Unable to Resolve";
                                                            break;

                                                        case TicketStatus::Resolved:
                                                            $btn_color = "success";
                                                            $btn_text = "Resolved";
                                                            break;
                                                    }
                                                ?>
                                                <button class="btn btn-<?PHP HTML::print($btn_color); ?> btn-xs dropdown-toggle pr-2 pl-2 text-small" type="button" id="status_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?PHP HTML::print($btn_text); ?></button>
                                                <div class="dropdown-menu" aria-labelledby="status_dropdown" x-placement="bottom-start">
                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_support_ticket', array('action' => 'set_status', 'id' => $_GET['id'], 'status' => 'opened'), true) ?>">Opened</a>
                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_support_ticket', array('action' => 'set_status', 'id' => $_GET['id'],  'status' => 'in_progress'), true) ?>">In Progress</a>
                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_support_ticket', array('action' => 'set_status', 'id' => $_GET['id'],  'status' => 'unable_to_resolve'), true) ?>">Unable to Resolve</a>
                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_support_ticket', array('action' => 'set_status', 'id' => $_GET['id'],  'status' => 'resolved'), true) ?>">Resolved</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-8 mb-3">
                                                <div class="form-group">
                                                    <label for="subject">Subject</label>
                                                    <input type="text" class="form-control" id="subject" placeholder="None" value="<?PHP HTML::print($SupportTicket->Subject); ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="message">Message</label>
                                                    <textarea class="form-control" id="message" rows="10" readonly><?PHP HTML::print($SupportTicket->Message); ?></textarea>
                                                </div>
                                                <div class="mt-4 border-bottom mb-4"></div>
                                                <form method="POST" action="<?PHP DynamicalWeb::getRoute('view_support_ticket', array('id' => $_GET['id'], 'action' => 'update_notes'), true) ?>">
                                                    <div class="form-group">
                                                        <label for="admin_notes">Administrator Notes</label>
                                                        <textarea class="form-control" name="admin_notes" id="admin_notes" rows="10"><?PHP HTML::print($SupportTicket->TicketNotes); ?></textarea>
                                                    </div>
                                                    <input type="submit" class="btn btn-success" value="Update Administrator Notes">
                                                </form>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-group mt-4">
                                                    <label for="ticket_id">ID</label>
                                                    <input type="text" class="form-control" id="ticket_id" placeholder="None" value="<?PHP HTML::print($SupportTicket->ID); ?>" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="ticket_number">Ticket Number</label>
                                                    <input type="text" class="form-control" id="ticket_number" placeholder="None" value="<?PHP HTML::print($SupportTicket->TicketNumber); ?>" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="contact_email">
                                                        Contact Email
                                                        <a class="pl-1 text-white" href="mailto:<?PHP HTML::print($SupportTicket->ResponseEmail); ?>">
                                                            <i class="mdi mdi-email"></i>
                                                        </a>
                                                    </label>
                                                    <input type="email" class="form-control" id="contact_email" placeholder="None" value="<?PHP HTML::print($SupportTicket->ResponseEmail); ?>" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="submission_timestamp">Submission Timestamp</label>
                                                    <input type="text" class="form-control" id="submission_timestamp" placeholder="None" value="<?PHP HTML::print(date("F j, Y, g:i a", $SupportTicket->SubmissionTimestamp)); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
    </body>
</html>