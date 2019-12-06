<?php


    namespace Support\Exceptions;

    use Exception;
    use Support\Abstracts\ExceptionCodes;

    /**
     * Class ConfigurationNotFoundException
     * @package Support\Exceptions
     */
    class ConfigurationNotFoundException extends Exception
    {
        /**
         * ConfigurationNotFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct('The configuration for the support library was not found', ExceptionCodes::ConfigurationNotFoundException, null);
        }
    }