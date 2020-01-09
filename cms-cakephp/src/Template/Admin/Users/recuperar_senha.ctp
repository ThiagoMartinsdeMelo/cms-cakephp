<?= $this->Form->create($user, ['class' => 'form-signin']) ?>
<h1 class="h3 mb-3 font-weight-normal">Recuperar Senha</h1>
<?= $this->Flash->render(); ?>
<div class="form-group">
    <label>E-mail</label>
    <?= $this->Form->control('email', ['class' => 'form-control', 'placeholder' => 'Seu Melhor E-mail', 'label' => false]); ?>        
</div>
<?= $this->Form->button(__('Recuperar'), ['class' => 'btn btn-lg btn-warning btn-block']) ?>
<p class="text-center">
    <?= $this->Html->link(__('Clique aqui'), ['controller' => 'Users', 'action' => 'login']); ?> para acessar
</p>
<?= $this->Form->end() ?>