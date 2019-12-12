<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;
use Cake\Utility\Security;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['cadastrar', 'logout', 'confEmail']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 40
        ];

        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
    }

    public function perfil()
    {
        $user_id = $this->Auth->user('id');
        $user = $this->Users->get($user_id);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->danger(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    use MailerAwareTrait;
    public function cadastrar()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->cod_val_email = Security::hash($this->request->getData('password').
            $this->request->getData('email'), 'sha256', false);
            if ($this->Users->save($user)) {
                $user->host_name = Router::fullBaseUrl().
                $this->request->getAttribute('webroot').$this->request->getParam('prefix');
                //$this->getMailer('User')->send('cadastroUser', [$user]);
                /*
                $msg = 'Caro(a) '.$user->name.'<br><br>Obrigado por se cadastrar.<br><br>';
                $email = new Email('envemail');
                $email->setTo($user->email)
                      ->setProfile('envemail')
                      ->setemailFormat('html')
                      ->setSubject(sprintf('Bem vindo'))
                      ->send($msg);
                */
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
            $this->Flash->danger(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    public function confEmail($cod_val_email = null)
    {
        $userTable = TableRegistry::get('Users');
        $confEmail = $userTable->getConfEmail($cod_val_email);
        if ($confEmail) {
            $user  = $this->Users->newEntity();
            $user->id = $confEmail->id;
            $user->email_val = '1';
            $userTable->save($user);
            if ($userTable->save($user)) {
                $this->Flash->success(__('Email confirmado com sucesso.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            } else {
                $this->Flash->danger(__('Erro: E-mail nao confirmado.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
        } else {
            $this->Flash->danger(__('Erro: E-mail nao confirmado.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'view', $user->id]);
            }
            $this->Flash->danger(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    public function alterarFotoUsuario($id = null)
    {
        $user = $this->Users->get($id);
        $imagemAntiga = $user->imagem;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->newEntity();
            $user->imagem = $this->Users->slugUploadImgRed($this->request->getData()['imagem']['name']);
            $user->id = $id;
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $destino = WWW_ROOT.'files'.DS.'user'.DS.$id.DS;
                $imgUpload = $this->request->getData()['imagem'];
                $imgUpload['name'] = $user->imagem;
                if ($this->Users->uploadImgRed($imgUpload, $destino, 150, 150)) {
                    $this->Users->deleteFile($destino, $imagemAntiga, $user->imagem);
                    $this->Flash->success(__('Foto editada com sucesso.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'view', $id]);
                } else {
                    $user->imagem = $imagemAntiga;
                    $this->Users->save($user);
                    $this->Flash->danger(__('Erro: Foto não foi editada com sucesso.'));
                }
            } else {
                $this->Flash->danger(__('Erro: Foto não foi editada com sucesso.'));
            }
        }
        $this->set(compact('user'));
    }

    public function editPerfil()
    {
        $user_id = $this->Auth->user('id');
        $user = $this->Users->get($user_id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Perfil editado com sucesso.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'perfil']);
            }
            $this->Flash->danger(__('Erro: Perfil não foi editado com sucesso.'));
        }
        $this->set(compact('user'));
    }

    public function editSenhaPerfil()
    {
        $user_id = $this->Auth->user('id');
        $user = $this->Users->get($user_id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Senha editada com sucesso.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'perfil']);
            }
            $this->Flash->danger(__('Erro: Senha não foi editada com sucesso.'));
        }    
        $this->set(compact('user'));
    }

    public function editSenha($id = null)
    {
        $user = $this->Users->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Senha do usuário editada com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->danger(__('Erro: A senha do usuario não foi editada com sucesso.'));
        }
        $this->set(compact('user'));
    }

    public function alterarFotoPerfil()
    {
        $user_id = $this->Auth->user('id');
        $user = $this->Users->get($user_id);
        $imagemAntiga = $user->imagem;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->newEntity();
            $user->imagem = $this->Users->slugUploadImgRed($this->request->getData()['imagem']['name']);
            $user->id = $user_id;
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $destino = WWW_ROOT.'files'.DS.'user'.DS.$user_id.DS;
                $imgUpload = $this->request->getData()['imagem'];
                $imgUpload['name'] = $user->imagem;
                if ($this->Users->uploadImgRed($imgUpload, $destino, 150, 150)) {
                    $this->Users->deleteFile($destino, $imagemAntiga, $user->imagem);
                    $this->Flash->success(__('Foto editada com sucesso.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'perfil']);
                } else {
                    $user->imagem = $imagemAntiga;
                    $this->Users->save($user);
                    $this->Flash->danger(__('Erro: Foto não foi editada com sucesso.'));
                }
            } else {
                $this->Flash->danger(__('Erro: Foto não foi editada com sucesso.'));
            }
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $destino = WWW_ROOT.'files'.DS.'user'.DS.$user->id.DS;
        $this->Users->deleteArq($destino);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('Usuário apagado com sucesso'));
        } else {
            $this->Flash->danger(__('Erro: Usuário não foi apagado com sucesso'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->danger(__('Erro: login ou senha incorreto.'));
            }
        }
    }

    public function logout()
    {
        $this->Flash->success(__('Deslogado com sucesso.'));
        return $this->redirect($this->Auth->logout());
    }
}
