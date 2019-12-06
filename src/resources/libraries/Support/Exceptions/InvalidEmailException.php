<?php


    namespace Support\Exceptions;

    use Exception;
    use Support\Abstracts\ExceptionCodes;

    /**
     * Class InvalidEmailException
     * @package Support\Exceptions
     */
    class InvalidEmailException extends Exception
    {
        /**
         * InvalidEmailException constructor.
         */
        public function __construct()
        {
            parent::__construct('The given email is invalid', ExceptionCodes::InvalidEmailException, null);
        }
    }