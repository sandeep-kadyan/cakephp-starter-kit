<p>Hello,</p>
<p>You requested to reset your password. Click the link below to choose a new password:</p>
<p>
    <a href="<?= $this->Url->build([
        'controller' => 'Users',
        'action' => 'resetPassword',
        '?' => ['token' => $authRequest->verification_token]
    ], ['fullBase' => true]) ?>">
        Reset your password
    </a>
</p>
<p>This link will expire in 30 minutes. If you did not request this, please ignore this email.</p>
