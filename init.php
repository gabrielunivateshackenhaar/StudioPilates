<?php
// init.php
// Arquivo que carrega a conexão e o controller

require_once 'db.php';
require_once __DIR__ . '/controller/StudentController.php';

$controller = new StudentController($pdo);
