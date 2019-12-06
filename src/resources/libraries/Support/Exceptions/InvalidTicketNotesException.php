<?php


    namespace Support\Exceptions;

    use Exception;
    use Support\Abstracts\ExceptionCodes;

    /**
     * Class InvalidTicketNotesException
     * @package Support\Exceptions
     */
    class InvalidTicketNotesException extends Exception
    {
        /**
         * InvalidTicketNotesException constructor.
         */
        public function __construct()
        {
            parent::__construct('The Ticket Note is too long to be valid', ExceptionCodes::InvalidTicketNotesException, null);
        }
    }