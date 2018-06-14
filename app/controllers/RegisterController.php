<?php

/**
 * SessionController
 *
 * Allows to register new users
 */
class RegisterController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    /**
     * Action to register a new user
     */
    public function indexAction()
    {
        $form = new RegisterForm;

        if ($this->request->isPost()) {

            $name = $this->request->getPost('name', array('string', 'striptags'));
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if ($password != $repeatPassword) {
                $this->flash->error('Passwords are different');
                return false;
            }

            $user = new Users();
            $user->password = sha1($password);
            $user->name = $name;
            $user->email = $email;
            $user->created_on = new Phalcon\Db\RawValue('now()');
            
            //setup for backend user preferences
            $user->status = 0;
            $user->last_login = new Phalcon\Db\RawValue('now()');
            $user->verify = uniqid();
            $user->admin = 0;
            $user->api_private = md5(uniqid(rand(), true));
            $user->timestamp = new Phalcon\Db\RawValue('now()');

            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->tag->setDefault('email', '');
                $this->tag->setDefault('password', '');
                $this->flash->success('Thank you for signing up, please log in.');
                return $this->forward('session/index');
            }
        }

        $this->view->form = $form;
    }
}
