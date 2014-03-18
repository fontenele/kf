<?php

namespace Admin\Controller;

Class Auth extends \KF\Lib\Module\Controller {

    public function init() {
        
    }

    public function login() {
        try {
            if ($this->request->isPost() && $this->request->post->offsetGet('email') && $this->request->post->offsetGet('senha')) {
                $service = new \Admin\Service\User();
                $logged = $service->auth($this->request->post->offsetGet('email'), md5($this->request->post->offsetGet('senha')));

                if ($logged) {
                    $session = new \KF\Lib\System\Session('system');
                    $photo = null;

                    if (\KF\Kernel::$config['system']['auth']['gravatar']) {
                        $gravatarUrl = 'http://www.gravatar.com/%s.php';
                        $gravatar = \unserialize(\file_get_contents(sprintf($gravatarUrl, md5($this->request->post->offsetGet('email')))));
                        if (is_array($gravatar) && isset($gravatar['entry']) && isset($gravatar['entry'][0])) {
                            $photo = $gravatar['entry'][0]['thumbnailUrl'];
                        }
                    }

                    $session->identity = array('email' => $this->request->post->offsetGet('email'), 'photo' => $photo);
                    \KF\Lib\View\Helper\Messenger::success('Bem vindo ' . $this->request->post->offsetGet('email'));
                    $this->redirect(\KF\Kernel::$router->default);
                } else {
                    \KF\Lib\View\Helper\Messenger::error('Login inválido');
                    $this->redirect('admin/auth/login');
                }
            }
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function logout() {
        try {
            session_destroy();
            \KF\Lib\View\Helper\Messenger::success('Volte sempre!');
            $this->redirect('admin/auth/login');
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
