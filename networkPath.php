#!/usr/local/bin/php
<?php
/**
 * Main entry point of the program
 */
include_once "DoublyLinkedListWithCount.php";
include_once "functions.php";

if (count($argv) < 2) {
    fwrite(STDOUT, "Wrong argument passed. Usage: php networkPath.php <filepath>\n");

    exit(0);
}

if (!file_exists($argv[1])) {
    fwrite(STDOUT, "File does not exists in the filepath {$argv[1]}\n");

    exit(0);
}

main($argv);
