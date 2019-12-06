<?php


    namespace Support\Managers;

    use Support\Abstracts\SupportTicketSearchMethod;
    use Support\Abstracts\TicketStatus;
    use Support\Exceptions\DatabaseException;
    use Support\Exceptions\InvalidEmailException;
    use Support\Exceptions\InvalidMessageException;
    use Support\Exceptions\InvalidSearchMethodException;
    use Support\Exceptions\InvalidSourceException;
    use Support\Exceptions\InvalidSubjectException;
    use Support\Exceptions\InvalidTicketNotesException;
    use Support\Exceptions\SupportTicketNotFoundException;
    use Support\Objects\SupportTicket;
    use Support\Support;
    use Support\Utilities\Hashing;
    use Support\Utilities\Validation;

    /**
     * Class TicketManager
     * @package Support\Managers
     */
    class TicketManager
    {
        /**
         * @var Support
         */
        private $support;

        /**
         * TicketManager constructor.
         * @param Support $support
         */
        public function __construct(Support $support)
        {
            $this->support = $support;
        }

        /**
         * @param string $source
         * @param string $subject
         * @param string $message
         * @param string $email
         * @return SupportTicket
         * @throws DatabaseException
         * @throws InvalidEmailException
         * @throws InvalidMessageException
         * @throws InvalidSearchMethodException
         * @throws InvalidSourceException
         * @throws InvalidSubjectException
         * @throws SupportTicketNotFoundException
         */
        public function submitTicket(string $source, string $subject, string $message, string $email): SupportTicket
        {
            if(Validation::source($source) == false)
            {
                throw new InvalidSourceException();
            }

            if(Validation::subject($subject) == false)
            {
                throw new InvalidSubjectException();
            }

            if(Validation::message($message) == false)
            {
                throw new InvalidMessageException();
            }

            if(Validation::email($email) == false)
            {
                throw new InvalidEmailException();
            }

            $CurrentTimestamp = time();
            $TicketNumber = Hashing::ticketNumber($source, $subject, $message, $CurrentTimestamp);
            $TicketNumber = $this->support->getDatabase()->real_escape_string($TicketNumber);
            $Source = $this->support->getDatabase()->real_escape_string($source);
            $Subject = $this->support->getDatabase()->real_escape_string($subject);
            $Message = $this->support->getDatabase()->real_escape_string($message);
            $ResponseEmail = $this->support->getDatabase()->real_escape_string($email);
            $TicketStatus = (int)TicketStatus::Opened;
            $TicketNotes = $this->support->getDatabase()->real_escape_string('None');

            $Query = "INSERT INTO `support_tickets` (ticket_number, source, subject, message, response_email, ticket_status, ticket_notes, submission_timestamp) VALUES ('$TicketNumber', '$Source', '$Subject', '$Message', '$ResponseEmail', $TicketStatus, '$TicketNotes', $CurrentTimestamp)";
            $QueryResults = $this->support->getDatabase()->query($Query);

            if($QueryResults == true)
            {
                return $this->getSupportTicket(SupportTicketSearchMethod::byTicketNumber, $TicketNumber);
            }

            throw new DatabaseException($Query, $this->support->getDatabase()->error);
        }

        /**
         * @param string $search_method
         * @param string $value
         * @return SupportTicket
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws SupportTicketNotFoundException
         */
        public function getSupportTicket(string $search_method, string $value): SupportTicket
        {
            switch($search_method)
            {
                case SupportTicketSearchMethod::byId:
                    $search_method = $this->support->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case SupportTicketSearchMethod::byTicketNumber:
                    $search_method = $this->support->getDatabase()->real_escape_string($search_method);
                    $value = "'" . $this->support->getDatabase()->real_escape_string($value) . "'";
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = "SELECT id, ticket_number, source, subject, message, response_email, ticket_status, ticket_notes, submission_timestamp FROM `support_tickets` WHERE $search_method=$value";
            $QueryResults = $this->support->getDatabase()->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->support->getDatabase()->error);
            }

            if ($QueryResults->num_rows !== 1)
            {
                throw new SupportTicketNotFoundException();
            }

            return SupportTicket::fromArray($QueryResults->fetch_array(MYSQLI_ASSOC));
        }

        /**
         * Updates an existing support ticket in the database
         *
         * @param SupportTicket $supportTicket
         * @return bool
         * @throws DatabaseException
         * @throws InvalidEmailException
         * @throws InvalidMessageException
         * @throws InvalidSearchMethodException
         * @throws InvalidSourceException
         * @throws InvalidSubjectException
         * @throws InvalidTicketNotesException
         * @throws SupportTicketNotFoundException
         */
        public function updateSupportTicket(SupportTicket $supportTicket): bool
        {
            // This will throw an exception if there is an issue trying to get the existing support ticket
            $this->getSupportTicket(SupportTicketSearchMethod::byId, $supportTicket->ID);

            if(Validation::email($supportTicket->ResponseEmail) == false)
            {
                throw new InvalidEmailException();
            }

            if(Validation::message($supportTicket->Message) == false)
            {
                throw new InvalidMessageException();
            }

            if(Validation::subject($supportTicket->Subject) == false)
            {
                throw new InvalidSubjectException();
            }

            if(Validation::source($supportTicket->Source) == false)
            {
                throw new InvalidSourceException();
            }

            if(Validation::note($supportTicket->TicketNotes) == false)
            {
                throw new InvalidTicketNotesException();
            }

            $ID = (int)$supportTicket->ID;
            $Source = $this->support->getDatabase()->real_escape_string($supportTicket->Source);
            $Subject = $this->support->getDatabase()->real_escape_string($supportTicket->Subject);
            $Message = $this->support->getDatabase()->real_escape_string($supportTicket->Message);
            $ResponseEmail = $this->support->getDatabase()->real_escape_string($supportTicket->ResponseEmail);
            $TicketStatus = (int)$supportTicket->TicketStatus;
            $TicketNotes = $this->support->getDatabase()->real_escape_string($supportTicket->TicketNotes);

            $Query = "UPDATE `support_tickets` SET source='$Source', subject='$Subject', message='$Message', response_email='$ResponseEmail', ticket_status=$TicketStatus, ticket_notes='$TicketNotes' WHERE id=$ID";
            $QueryResults = $this->support->getDatabase()->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->support->getDatabase()->error);
            }

            return true;
        }
    }