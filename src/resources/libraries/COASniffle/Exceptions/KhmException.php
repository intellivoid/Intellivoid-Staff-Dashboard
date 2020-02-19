<?php


    namespace COASniffle\Exceptions;


    use Exception;

    /**
     * Class KhmException
     * @package COASniffle\Exceptions
     */
    class KhmException extends Exception
    {
        /**
         * @var string
         */
        private $response_raw;

        /**
         * @var array
         */
        private $parameters;

        /**
         * @var int
         */
        private $status_code;

        /**
         * @var string
         */
        private $error_message;

        /**
         * KhmException constructor.
         * @param string $response_raw
         * @param array $parameters
         * @param int $status_code
         * @param string $message
         */
        public function __construct(string $response_raw, array $parameters, int $status_code, string $message)
        {
            parent::__construct("There was an error with the KHM response", 0, null);
            $this->response_raw = $response_raw;
            $this->parameters = $parameters;
            $this->status_code = $status_code;
            $this->error_message = $message;
        }
    }