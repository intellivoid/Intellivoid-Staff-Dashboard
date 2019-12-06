<?php


    namespace Support\Abstracts;


    /**
     * Class SupportTicketSearchMethod
     * @package Support\Abstracts
     */
    abstract class SupportTicketSearchMethod
    {
        /**
         * Searches support tickets by ID
         */
        const byId = 'id';

        /**
         * Searches support tickets by Ticket Number
         */
        const byTicketNumber = 'ticket_number';
    }