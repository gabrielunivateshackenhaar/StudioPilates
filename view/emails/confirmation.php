<?php
// view/emails/confirmation.php
// Variáveis disponíveis: $name, $confirmationCode
?>
<div style="font-family: sans-serif; color: #333; max-width: 600px; margin: 0 auto;">
    <h2>Bem-vindo(a), <?= htmlspecialchars($name) ?>!</h2>
    
    <p>Obrigado por se cadastrar no Studio Pilates.</p>
    <p>Para ativar sua conta, use o código de verificação abaixo:</p>
    
    <div style="background: #f3f3f3; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 4px; border-radius: 8px; margin: 20px 0;">
        <?= htmlspecialchars($confirmationCode) ?>
    </div>
    
    <p style="font-size: 0.9em; color: #666;">Se você não solicitou este cadastro, por favor ignore este e-mail.</p>
</div>