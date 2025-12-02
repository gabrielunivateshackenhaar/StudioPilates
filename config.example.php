<?php
// config.example.php
// Copie este arquivo para "config.php" (crie o config.php na raíz) e preencha com suas credenciais.

// username = endereço de e-mail
// password = senha de app gerada

// Extension missing: openssl
// É preciso descomentar a propriedade "extension=openssl" no php.ini para evitar esse erro

return [
    'email' => [
        'host'     => 'smtp.gmail.com',
        'username' => 'seu_email@gmail.com',
        'password' => 'sua_senha_de_app',    
        'port'     => 465,
        'from_name'=> 'Studio Pilates'
    ]
];