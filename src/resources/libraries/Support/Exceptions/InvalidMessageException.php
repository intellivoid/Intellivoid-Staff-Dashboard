<?php


    namespace Support\Exceptions;

    use Exception;
    use Support\Abstracts\ExceptionCodes;

    /**
     * Class InvalidMessageException
     * @package Support\Exceptions
     */
    class InvalidMessageException extends Exception
    {
        /**
         * InvalidMessageException constructor.
         */
        public function __construct()
        {
            parent::__construct('The given message is invalid, it cannot be less than 20 characters or greater than 2500', ExceptionCodes::InvalidMessageException, null);
        }
    }