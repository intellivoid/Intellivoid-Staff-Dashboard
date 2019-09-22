<?php

    use DynamicalWeb\HTML;

    /**
     * Renders a alert in the document
     *
     * @param string $text
     * @param string $type
     * @param string $icon
     */
    function RenderAlert(string $text, string $type, string $icon)
    {
        HTML::print("<div class=\"alert alert-fill-", false);
        HTML::print($type);
        HTML::print("\" role=\"alert\">", false);
        HTML::print("<i class=\"mdi ", false);
        HTML::print($icon);
        HTML::print("\"></i>", false);
        HTML::print($text);
        HTML::print("</div>", false);
    }