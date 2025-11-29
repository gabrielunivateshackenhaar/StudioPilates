<?php
// utils/Email.php

// Carrega os arquivos do PHPMailer manualmente
require_once __DIR__ . '/../lib/PHPMailer/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Email {

    private $config;

    public function __construct() {
        // Carrega as configurações do arquivo config.php
        
        $configFile = __DIR__ . '/../config.php';

        if (file_exists($configFile)) {
            $this->config = require $configFile;
        } else {
            die("Erro: Arquivo config.php não encontrado. Copie o config.example.php.");
        }
    }

    /**
     * Envia um e-mail usando o servidor SMTP do Gmail.
     *
     * @param string $toEmail E-mail do destinatário
     * @param string $toName Nome do destinatário
     * @param string $subject Assunto do e-mail
     * @param string $htmlMessage Corpo da mensagem (suporta tags HTML)
     * @return bool Retorna true se enviado com sucesso, false caso contrário
     */
    public function send(string $toEmail, string $toName, string $subject, string $htmlMessage): bool {
        // Cria uma nova instância do PHPMailer
        // Passar 'true' habilita o lançamento de exceções em caso de erro
        $mail = new PHPMailer(true);

        try {
            // Pega as credenciais do array de configuração
            $emailConfig = $this->config['email'];
            
            // Descomente a linha abaixo para ver o log de erros no navegador se algo falhar
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // ou apenas 2

            $mail->isSMTP();                                  // Define o uso de SMTP
            $mail->Host       = $emailConfig['host'];         // Servidor SMTP do Gmail
            $mail->SMTPAuth   = true;                         // Habilita autenticação SMTP
            $mail->Username   = $emailConfig['username'];     // E-mail do Remetente (Login)
            $mail->Password   = $emailConfig['password'];     // Senha de App
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Habilita encriptação TLS implícita
            $mail->Port       = $emailConfig['port'];         // Porta TCP (465 para SSL/TLS)
            $mail->CharSet    = 'UTF-8';                      // Suporte para acentos e caracteres especiais

            // Define quem está enviando o e-mail
            $mail->setFrom($emailConfig['username'], $emailConfig['from_name']);
            
            // Define quem vai receber
            $mail->addAddress($toEmail, $toName);

            // Conteúdo
            $mail->isHTML(true);                        // Define formato do e-mail como HTML
            $mail->Subject = $subject;                  // Assunto
            $mail->Body    = $htmlMessage;              // Corpo da mensagem com HTML
            $mail->AltBody = strip_tags($htmlMessage);  // Versão em texto puro (para clientes que não aceitam HTML)

            // Envia o e-mail
            $mail->send();
            return true;

        } catch (Exception $e) {
            // Em caso de erro, retorna false.
            // Você pode descomentar a linha abaixo para ver o erro exato durante o desenvolvimento:
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}