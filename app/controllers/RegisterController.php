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
        //$this->sendVerify('1234', 'Scott', 'scott@huson.com' );
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

            //uniqid() for verification
            $verify = uniqid();
            $user->verify = $verify;
            $user->admin = 0;
            $user->api_private = md5(uniqid(rand(), true));
            $user->timestamp = new Phalcon\Db\RawValue('now()');

            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->tag->setDefault('email', 'bru.no@brown.edu');
                $this->tag->setDefault('password', '');
                $this->sendVerify($verify, $name, $email);
                $this->flash->success('Thank you for signing up, a verification link has been emailed to you.');
                return $this->forward('session/index');
            }
        }

        $this->view->form = $form;
    }

    public function sendVerify($uniq, $name, $email) {
        $content = "Dear ".$name.",<br/><br/>Thank you for signing up for Brown Bytes! This is a verification email to verify that your veriable identity has been verified. Click the link below to confirm that your created this account.<br/><a target='_blank' href='http://www.brown-bytes.org/session/verify/".$uniq."'>Check me out, im a link!</a><br/><br/>If you didn't create this account, consider creating one for real at 'www.brownbytes.org'.<br/>If you would like to contact the Brown Bytes gods, go to <a target='_blank' href='http://www.brown-bytes.org/contact'>the Contact page</a> on the Brown Bytes website.<br/><br/>Thank you for your time,<br/>B. B. g.<br/><i>Brown Bytes god</i>";

        mail($email, 'Brown Bytes Account verification', $content);
    }
}
