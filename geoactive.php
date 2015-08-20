<?php
require 'workflow.php';
require 'functions.php';

isset ( $argv [1] ) && $query = trim ( $argv [1] );
$workflow = new Workflows ();

$res = $workflow->write ( $query, 'google_map_api_key' );
$title = $res ? 'success' : 'failure';
$workflow->result ( $query, $query, $title, 'set google api key failure', 'icon.png' );