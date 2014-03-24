<?php

namespace Admin\Controller;

Class Auth extends \KF\Lib\Module\Controller {

    public function init() {
        
    }

    public function login() {
        try {
            if ($this->request->isPost() && $this->request->post->offsetGet('email') && $this->request->post->offsetGet('senha')) {
                $service = new \Admin\Service\User();
                $logged = $service->findOneBy(['user_group.status' => '1', 'status' => '1', 'email' => $this->request->post->offsetGet('email'), 'password' => md5($this->request->post->offsetGet('senha'))], ['logged' => 'count(1)'])['logged'];

                if ($logged) {
                    $session = new \KF\Lib\System\Session('system');
                    $user = $service->findOneByEmail('guilherme@fontenele.net');
                    unset($user['password']);
                    $user['photo'] = null;

                    if (\KF\Kernel::$config['system']['auth']['gravatar']) {
                        $gravatarUrl = 'http://www.gravatar.com/%s.php';
                        $gravatar = \unserialize(\file_get_contents(sprintf($gravatarUrl, md5($this->request->post->offsetGet('email')))));
                        if (is_array($gravatar) && isset($gravatar['entry']) && isset($gravatar['entry'][0])) {
                            $user['photo'] = $gravatar['entry'][0]['thumbnailUrl'];
                        }
                    }

                    $session->identity = $user;

                    \KF\Lib\System\Messenger::success('Bem vindo ' . $this->request->post->offsetGet('email'));
                    $this->redirect(\KF\Kernel::$router->default);
                } else {
                    \KF\Lib\System\Messenger::error('Login inválido');
                    $this->redirect('admin/auth/login');
                }
            }
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function logout() {
        try {
            session_destroy();
            $this->redirect('admin/auth/login');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
