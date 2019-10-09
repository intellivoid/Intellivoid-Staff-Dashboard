<?php
    $DisabledAnimations = false;
    if(isset($_GET['animations']))
    {
        if($_GET['animations'] == 'disabled')
        {
            $DisabledAnimations = true;
        }
    }
    if($DisabledAnimations == false)
    {
        ?>
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <?PHP
    }