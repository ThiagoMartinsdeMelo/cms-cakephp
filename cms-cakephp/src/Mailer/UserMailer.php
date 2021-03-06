<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

/**
 * User mailer.
 */
class UserMailer extends Mailer
{
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static $name = 'User';

    public function cadastroUser($user)
    {

        $this->setTo($user->email)
              ->setProfile('envemail')
              ->setemailFormat('html')
              ->setTemplate('welcome')
              ->setLayout('user')
              ->setViewVars(['name' => $user->name, 'cod_val_email' => $user->cod_val_email, 'host_name' => $user->host_name ])
              ->setSubject(sprintf('Bem vindo'));
    }

}
