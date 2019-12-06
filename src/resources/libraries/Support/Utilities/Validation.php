<?php


    namespace Support\Utilities;

    /**
     * Class Validation
     * @package Support\Utilities
     */
    class Validation
    {

        /**
         * Indicates if the given subejct is valid or not
         *
         * @param string $input
         * @return bool
         */
        public static function subject(string $input): bool
        {
            if(strlen($input) < 5)
            {
                return false;
            }

            if(strlen($input) > 130)
            {
                return false;
            }

            return true;
        }

        /**
         * @param string $input
         * @return bool
         */
        public static function message(string $input): bool
        {
            if(strlen($input) < 20)
            {
                return false;
            }

            if(strlen($input) > 2500)
            {
                return false;
            }

            return true;
        }

        /**
         * Validates the source format
         *
         * @param string $input
         * @return bool
         */
        public static function source(string $input): bool
        {
            if(strlen($input) < 1)
            {
                return false;
            }

            if(strlen($input) > 126)
            {
                return false;
            }

            return true;
        }

        /**
         * Validates if the email is valid or not
         *
         * @param string $input
         * @return bool
         */
        public static function email(string $input): bool
        {
            if(filter_var($input, FILTER_VALIDATE_EMAIL) == false)
            {
                return false;
            }

            if(strlen($input) > 200)
            {
                return false;
            }

            return true;
        }

        /**
         * Validates if the note is valid or not
         *
         * @param string $input
         * @return bool
         */
        public static function note(string $input): bool
        {
            if(strlen($input) > 2500)
            {
                return false;
            }

            return true;
        }
    }