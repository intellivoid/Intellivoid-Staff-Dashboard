<?php


    namespace Support\Exceptions;

    use Exception;
    use Support\Abstracts\ExceptionCodes;

    /**
     * Class InvalidSubjectException
     * @package Support\Exceptions
     */
    class InvalidSubjectException extends Exception
    {
        /**
         * InvalidSubjectException constructor.
         */
        public function __construct()
        {
            parent::__construct('The given subject is invalid, it cannot be less than 5 characters but no greater than 130', ExceptionCodes::InvalidSubjectException, null);
        }
    }