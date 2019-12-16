<?php

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'create_subscription')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                create_subscription_plan();
            }
        }
    }

    function create_subscription_plan()
    {
        if(isset($_POST['plan_name']) == false)
        {
            // Bad Request
        }

        if(isset($_POST['initial_price']))
        {
            // Bad Request
        }

        if(isset($_POST['cycle_price']))
        {
            // Bad Request
        }

        if(isset($_POST['unix_billing_price']))
        {
            // Bad Request
        }
    }