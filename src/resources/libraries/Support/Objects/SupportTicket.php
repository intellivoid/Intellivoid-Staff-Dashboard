<?php


    namespace Support\Objects;

    /**
     * Class SupportTicket
     * @package Support\Objects
     */
    class SupportTicket
    {
        /**
         * The internal Database ID for this object
         *
         * @var int
         */
        public $ID;

        /**
         * The public ticket number
         *
         * @var string
         */
        public $TicketNumber;

        /**
         * The source of this support ticket
         *
         * @var string
         */
        public $Source;

        /**
         * The subject of the ticket
         *
         * @var string
         */
        public $Subject;

        /**
         * The message of the ticket
         *
         * @var string
         */
        public $Message;

        /**
         * The email used to contact the user who submitted this ticket
         *
         * @var string
         */
        public $ResponseEmail;

        /**
         * The current status of the ticket
         *
         * @var int
         */
        public $TicketStatus;

        /**
         * Optional administrator notes regarding this ticket
         *
         * @var string
         */
        public $TicketNotes;

        /**
         * The Unix Timestamp of when this Ticket was submitted
         *
         * @var int
         */
        public $SubmissionTimestamp;

        /**
         * Converts this object to an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->ID,
                'ticket_number' => $this->TicketNumber,
                'source' => $this->Source,
                'subject' => $this->Subject,
                'message' => $this->Message,
                'response_email' => $this->ResponseEmail,
                'ticket_status' => (int)$this->TicketStatus,
                'ticket_notes' => $this->TicketNotes,
                'submission_timestamp' => (int)$this->SubmissionTimestamp
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return SupportTicket
         */
        public static function fromArray(array $data): SupportTicket
        {
            $SupportTicketObject = new SupportTicket();

            if(isset($data['id']))
            {
                $SupportTicketObject->ID = (int)$data['id'];
            }

            if(isset($data['ticket_number']))
            {
                $SupportTicketObject->TicketNumber = $data['ticket_number'];
            }

            if(isset($data['source']))
            {
                $SupportTicketObject->Source = $data['source'];
            }

            if(isset($data['subject']))
            {
                $SupportTicketObject->Subject = $data['subject'];
            }

            if(isset($data['message']))
            {
                $SupportTicketObject->Message = $data['message'];
            }

            if(isset($data['response_email']))
            {
                $SupportTicketObject->ResponseEmail = $data['response_email'];
            }

            if(isset($data['ticket_status']))
            {
                $SupportTicketObject->TicketStatus = (int)$data['ticket_status'];
            }

            if(isset($data['ticket_notes']))
            {
                $SupportTicketObject->TicketNotes = $data['ticket_notes'];
            }

            if(isset($data['submission_timestamp']))
            {
                $SupportTicketObject->SubmissionTimestamp = (int)$data['submission_timestamp'];
            }

            return $SupportTicketObject;
        }
    }