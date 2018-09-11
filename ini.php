<?php
date_default_timezone_set('Europe/Moscow');
require_once '../vendor/autoload.php';

use Medoo\Medoo;
// Initialize DB for dev keeping production db safe, see in .gitignore
$file = '../storage/database.db';
if (is_writable('../storage/database.local.db')) {
  $file = '../storage/database.local.db';
}

$database = new Medoo([
  'database_type' => 'sqlite',
  'database_file' => $file
]);

$comment = new KK\Comment($database);