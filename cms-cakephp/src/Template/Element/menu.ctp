<nav class="sidebar">
    <ul class="list-unstyled">
        <li>
            <?= $this->Html->link(
                '<i class="fas fa-tachometer-alt"></i> Dashboard',
                [
                    'controller' => 'welcome',
                    'action' => 'index'
                ],
                [
                    'escape' => false
                ]
                );
            ?>
            <?= $this->Html->link(
                '<i class="fas fa-users"></i> Usuarios',
                [
                    'controller' => 'users',
                    'action' => 'index'
                ],
                [
                    'escape' => false
                ]
                );
            ?>
            <?= $this->Html->link(
                '<i class="fas fa-sign-out-alt"></i> Sair',
                [
                    'controller' => 'users',
                    'action' => 'logout'
                ],
                [
                    'escape' => false
                ]
                );
            ?>
    </ul>
</nav>