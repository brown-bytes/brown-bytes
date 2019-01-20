<?php


/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    public function indexAction()
    {
        if (!$this->request->isPost()) {
            $this->tag->setDefault('email', 'paxton@brown.edu');
            $this->tag->setDefault('password', 'password');
        }
        if ($this->session->get('auth')) {
            return $this->forward('dashboard');
        }
    }

    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession(Users $user)
    {
        $this->session->set('auth', array(
            'id' => $user->getId(),
            'email' => $user->email,
            'admin' => $user->isAdmin(),
            'data' => array(
                'name' => $user->name,
                'status' => $user->status,
            )
        ));

        if ($user->isAdmin()) {
            $this->session->admin = true;
        }
    }

    /**
     * This action authenticate and logs an user into the application
     *
     */
    public function startAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            $user = Users::findFirst(
                array( //emails are unique so its fine
                    "(email = :email:)",
                    'bind' => array('email' => $email) 
                )
            );
            if(!$user) { //If there are no emails in the database with this name
                $this->flash->error("There is no account registered to that email.");
                $this->flash->notice("Due to recent changes to password encryption (making it more secure), were asking all users with accounts to re-register.
                    Sorry for the inconvinience.");
                return $this->forward('session/index');
            } else if($user->status < 1) {
                $this->flash->error("Please verify your account by clicking the link in the email that was sent to you.");
                return $this->forward('session/index');
            }
            $access = $user->verifyPassword($password);


            if ($access) { 
                $this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->name);
                return $this->forward('dashboard/index');
            } else if ($email == "paxton@brown.edu") {
                $this->flash->error("I'm sorry President Paxton, this platform is not for you.");
            } else {
                $this->flash->error('Wrong email/password');
            }
        }

        return $this->forward('session/index');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {   
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->forward('index/index');
    }
    public function verifyAction($key) {
        $user = Users::findFirst(
            array(
                "verify = :uniqid: AND status = '0'",
                "bind" => array('uniqid' => $key)
            )
        );
        if($user != false){
            $user->status = 1;
            if($user->save() == false) {
                $this->flash->success("Internal error could not verify");
                $this->forward('session/index');
            }
            $this->flash->success("Thank you for verifying ".$user->name.".");
            $this->forward('session/index');
        } else {
            $this->forward('session/index');
        }

    }
    public function dashboardAction() {
        return $this->forward('dashboard/index');
    }
}
