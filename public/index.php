<?php
require_once '../vendor/autoload.php';

// Using Medoo namespace
use Medoo\Medoo;

// Initialize
$database = new Medoo([
	'database_type' => 'sqlite',
	'database_file' => '../storage/database.db'
]);

dump($database);