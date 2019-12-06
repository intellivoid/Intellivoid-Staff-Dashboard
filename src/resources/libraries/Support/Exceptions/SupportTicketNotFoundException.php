<?php


    namespace Support\Exceptions;

    use Exception;
    use Support\Abstracts\ExceptionCodes;

    /**
     * Class SupportTicketNotFoundException
     * @package Support\Exceptions
     */
    class SupportTicketNotFoundException extends Exception
    {
        /**
         * SupportTicketNotFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct('The requested support ticket was not found in the Database', ExceptionCodes::SupportTicketNotFoundException, null);
        }
    }