<?php


    namespace Support\Utilities;


    class Hashing
    {
        /**
         * Peppers a hash using whirlpool
         *
         * @param string $Data The hash to pepper
         * @param int $Min Minimal amounts of executions
         * @param int $Max Maximum amount of executions
         * @return string
         */
        public static function pepper(string $Data, int $Min = 100, int $Max = 1000): string
        {
            $n = rand($Min, $Max);
            $res = '';
            $Data = hash('whirlpool', $Data);
            for ($i=0,$l=strlen($Data) ; $l ; $l--)
            {
                $i = ($i+$n-1) % $l;
                $res = $res . $Data[$i];
                $Data = ($i ? substr($Data, 0, $i) : '') . ($i < $l-1 ? substr($Data, $i+1) : '');
            }
            return($res);
        }

        /**
         * Calculates the Ticket Numbers
         *
         * @param string $source
         * @param string $subject
         * @param string $message
         * @param int $timestmap
         * @return string
         */
        public static function ticketNumber(string $source, string $subject, string $message, int $timestmap)
        {
            $subject = self::pepper($subject);
            $message = self::pepper($message);

            $cef = hash('ripemd128', self::pepper($source) . $source);
            return strtoupper(hash('ripemd160', $cef . $timestmap));
        }
    }