<p>Hello,</p>
<p>You requested to log in using a magic link. Click the link below to verify your login:</p>
<p>
    <a href="<?= $this->Url->build([
        'controller' => 'Users',
        'action' => 'verify',
        '?' => ['token' => $authRequest->verification_token]
    ], ['fullBase' => true]) ?>">
        Log in with this magic link
    </a>
</p>
<p>If you did not request this, please ignore this email.</p> 