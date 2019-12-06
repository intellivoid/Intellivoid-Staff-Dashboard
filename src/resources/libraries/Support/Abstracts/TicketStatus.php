<?php


    namespace Support\Abstracts;

    /**
     * Class TicketStatus
     * @package Support\Abstracts
     */
    abstract class TicketStatus
    {
        /**
         * The ticket is opened, and no action has been taken yet
         */
        const Opened = 0;

        /**
         * The ticket has been acknowledged, a resolution is in progress
         */
        const InProgress = 1;

        /**
         * The ticket has been acknowledged, but no resolution was found.
         */
        const UnableToResolve = 2;

        /**
         * The ticket has been resolved
         */
        const Resolved = 3;
    }