Hello,

You requested to reset your password. Click the link below to choose a new password:

<?= $this->Url->build([
    'controller' => 'Users',
    'action' => 'resetPassword',
    '?' => ['token' => $authRequest->verification_token]
], ['fullBase' => true]) ?>

This link will expire in 30 minutes. If you did not request this, please ignore this email.
