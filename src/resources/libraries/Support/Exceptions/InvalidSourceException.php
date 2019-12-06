<?php


    namespace Support\Exceptions;

    use Support\Abstracts\ExceptionCodes;

    /**
     * Class InvalidSourceException
     * @package Support\Exceptions
     */
    class InvalidSourceException extends \Exception
    {
        /**
         * InvalidSourceException constructor.
         */
        public function __construct()
        {
            parent::__construct('The source is invalid', ExceptionCodes::InvalidSourceException, null);
        }
    }