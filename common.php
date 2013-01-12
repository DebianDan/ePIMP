<?php

function fatalErrorContactMatt( $message ){
    echo '<h3>There was a fatal error</h3>';
    echo '<p>This was embarassing for us unless you\'re being cheeky. Your best bet here is to find an Expensify employee and ask them for Matt.</p>';
    echo '<p>Helpful information: ' . $message;
    die();
}
