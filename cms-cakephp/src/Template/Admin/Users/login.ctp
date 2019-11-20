<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form large-12 medium-12 columns content">
    <h1>Login</h1>
    <br>
    <?= $this->Form->create() ?>
    <fieldset>
        <?php
            echo $this->Form->control('username');
            echo $this->Form->control('password');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Acessar')) ?>
    <?= $this->Form->end() ?>
</div>
