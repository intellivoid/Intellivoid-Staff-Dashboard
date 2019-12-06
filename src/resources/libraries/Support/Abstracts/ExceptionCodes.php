<?php


    namespace Support\Abstracts;

    /**
     * Class ExceptionCodes
     * @package Support\Abstracts
     */
    abstract class ExceptionCodes
    {
        const ConfigurationNotFoundException = 100;

        const InvalidSubjectException = 101;

        const InvalidMessageException = 102;

        const InvalidSourceException = 103;

        const DatabaseException = 104;

        const InvalidEmailException = 105;

        const InvalidSearchMethodException = 106;

        const SupportTicketNotFoundException = 107;

        const InvalidTicketNotesException = 108;
    }