<?php
// init.php
// Arquivo que carrega a conexão e o controller

require_once __DIR__ . '/db/db.php';
require_once __DIR__ . '/controller/UserController.php';

$controller = new UserController($pdo);
