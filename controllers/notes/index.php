<?php

use core\Database;

$config = require base_path('config.php');

$db = new Database($config['database']);

$notes = $db->query('SELECT * from notes where user_id =1  ')->get();

require view("notes/index.view.php" , [
    "heading"=> 'My Notes',
    'notes'=> $notes
]);