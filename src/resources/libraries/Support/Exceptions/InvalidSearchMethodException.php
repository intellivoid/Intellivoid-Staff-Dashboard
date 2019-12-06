<?php


    namespace Support\Exceptions;

    use Exception;
    use Support\Abstracts\ExceptionCodes;

    /**
     * Class InvalidSearchMethodException
     * @package Support\Exceptions
     */
    class InvalidSearchMethodException extends Exception
    {
        /**
         * InvalidSearchMethodException constructor.
         */
        public function __construct()
        {
            parent::__construct('The given search method is invalid', ExceptionCodes::InvalidSearchMethodException, null);
        }
    }