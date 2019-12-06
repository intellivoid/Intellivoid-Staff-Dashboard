<?php


    namespace Support\Exceptions;

    use Exception;
    use Support\Abstracts\ExceptionCodes;

    /**
     * Class DatabaseException
     * @package Support\Exceptions
     */
    class DatabaseException extends Exception
    {
        /**
         * @var string
         */
        private $error;
        /**
         * @var string
         */
        private $query;

        /**
         * DatabaseException constructor.
         * @param string $query
         * @param string $error
         */
        public function __construct(string $query, string $error)
        {
            parent::__construct('Internal Database Exception', ExceptionCodes::DatabaseException, null);
            $this->query = $query;
            $this->error = $error;
        }

        /**
         * @return string
         */
        public function getError(): string
        {
            return $this->error;
        }

        /**
         * @return string
         */
        public function getQuery(): string
        {
            return $this->query;
        }
    }