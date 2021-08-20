<?php
/**
 * Glabā pielietojāmās funkcijas
 */


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Saprot vai $_GET['page'] ir vienāds ar padoto tekstu ($page_name)
 * ja ir vienāds tad klasix
 * 
 * @return string $class 'active' || '' || {{padotais klases nosaukums}}
 */
function isPage(string $page_name, string $class = 'active') {
    if (isset($_GET['page']) && $_GET['page'] === $page_name) {
        return $class;
    }
    return '';
}

function getPageName () {
    if (
        array_key_exists('page', $_GET) &&
        is_string($_GET['page']) &&
        array_key_exists($_GET['page'], array_flip(['design', 'code']))
    ) {
        return $_GET['page'];
    }
    else {
        return 'design';
    }
}


