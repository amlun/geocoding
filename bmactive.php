<?php
require 'workflow.php';
require 'functions.php';
require 'BaiduMapClient.php';

isset ( $argv [1] ) && $query = trim ( $argv [1] );
$workflow = new Workflows ();

$res = $workflow->write ( $query, BaiduMapClient::APIKEY );
$title = $res ? 'success' : 'failure';
$workflow->result ( $query, $query, $title, 'set baidu api key failure', 'icon.png' );
print $workflow->toxml ();