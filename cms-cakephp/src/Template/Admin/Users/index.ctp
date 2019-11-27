<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="d-flex">
    <div class="mr-auto p-2">
        <h2 class="display-4 titulo">Listar Usuários</h2>
    </div>
    <div class="p-2">
        <?= $this->Html->link(__('Cadastrar'), ['controller' => 'users', 'action' => 'add'], ['class' => 'btn btn-outline-success btn-sm']); ?>
    </div>
</div>
<?= $this->Flash->render() ?>
<div class="alert alert-success" role="alert">
    Usuário apagado com sucesso!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th class="d-none d-sm-table-cell">E-mail</th>
                <th class="d-none d-lg-table-cell">Data do Cadastro</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <th><?= $this->Number->format($user->id) ?></th>
                    <td><?= h($user->name) ?></td>
                    <td class="d-none d-sm-table-cell"><?= h($user->email) ?></td>
                    <td class="d-none d-lg-table-cell"><?= h($user->created) ?></td>
                    <td class="text-center">
                        <span class="d-none d-md-block">
                            <?= $this->Html->link(__('Visualizar'), ['action' => 'view', $user->id], ['class' => 'btn btn-outline-primary btn-sm']); ?>
                            <?= $this->Html->link(__('Editar'), ['action' => 'edit', $user->id], ['class' => 'btn btn-outline-warning btn-sm']); ?>
                            <?= $this->Form->postLink(__('Apagar'), ['action' => 'delete', $user->id], ['class' => 'btn btn-outline-danger btn-sm', 'data-toggle' => 'modal', 'data-target' => '#apagarRegistro', 'confirm' => __('Are you sure you want to delete # {0}?', $user->name)]); ?>
                        </span>
                        <div class="dropdown d-block d-md-none">
                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="acoesListar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Ações
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="acoesListar">
                                <?= $this->Html->link(__('Visualizar'), ['action' => 'view', $user->id], ['class' => 'dropdown-item']); ?>
                                <?= $this->Html->link(__('Editar'), ['action' => 'edit', $user->id], ['class' => 'dropdown-item']); ?>
                                <?= $this->Form->postLink(__('Apagar'), ['action' => 'delete', $user->id], ['class' => 'dropdown-item', 'data-toggle' => 'modal', 'data-target' => '#apagarRegistro', 'confirm' => __('Are you sure you want to delete # {0}?', $user->name)]); ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->element('pagination'); ?>
</div>