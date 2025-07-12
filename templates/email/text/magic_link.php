Hello,

You requested to log in using a magic link. Use the link below to verify your login:

<?= $this->Url->build([
    'controller' => 'Users',
    'action' => 'verify',
    '?' => ['token' => $authRequest->verification_token]
], ['fullBase' => true]) ?>

If you did not request this, please ignore this email. 