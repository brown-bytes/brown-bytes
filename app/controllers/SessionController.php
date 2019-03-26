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

        $user->last_login = date('y-m-d H:i:s');
        if ($user->save() == false) {
            $this->flash->error("Could not save time.");

        }
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
                $this->flash->notice("Due to recent changes to password encryption (making it more secure), were asking all users with accounts to <a href='/session/tryreset'>reset</a> their password.
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
                $this->flash->error("Wrong email/password, if you forgot your password, try resetting.");
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
            $user->status = 1; //Set status of user to active
            $user->verify = uniqid(); //Reset key so that users cannot change password with same id. 
            if($user->save() == false) {
                $this->flash->success("Internal error could not verify");
                $this->forward('session/index');
            }
            $this->flash->success("Thank you for verifying ".$user->name.".");  
        }
        return $this->forward('session/index');
    }
    public function dashboardAction() {
        return $this->forward('dashboard/index');
    }
    //This function runs when the user looks at reset form.
    public function tryresetAction() {
        return true;
    }
    //This function receives the information to send an email to a user. 
    public function resetAction() {
        if ($this->request->isPost()) {
            //Gets the information from the request, sees if its an email, and sees if there is an email associated with that request. 
            $email = $this->request->getPost('email');
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->flash->error("This is not an email :(");
                return $this->forward('session/tryreset');
            }
            $user = Users::findFirst(
                array( //emails are unique so its fine
                    "(email = :email:)",
                    'bind' => array('email' => $email) 
                )
            );
            //The idea with this is to not give the user an idea of whether that email is an account. 
            if($user) { //This means there is a user with that email
                $user->verify = uniqid(); //Reset the verify string that was used upon registration
                $user->verify_timer = time();
                if($user->save() == false) {
                    $this->flash->success("Internal error could not verify");
                    return $this->forward('session/index');
                }
                //Send verification email:
                $notice = new Mailer('scott@huson.com', 'Someone Reset Password', 'Name: '.$user->name.'<br/>Email: '.$user->email.'<br/>END.');
                $email = new Mailer($user->email, 'Requested Password Reset', 'Hi '.$user->name.',<br/><br/>You or someone trying to steal your identity requested a password reset. We are happy to have you back!<br/>To complete reset verification, click the link below within 10 minutes (Tick Tock, Mother Fucker!).<br/>http://brownbytes.org/session/verifyreset/'.$user->verify.'<br/><br/>Best,<br/>The Brown Bytes Team');
            }
            $this->flash->success("If there is an account with that email, an email has been sent to it.");
        }
        return $this->forward('session/index');
    }
    //This function receives the information sent in an email to the user and then allows the user to reset their password.
    public function verifyresetAction($verify) {
        if(!$verify) {
            return $this->forward('session/index');
        }
        $this->view->verify = $verify;
    }
    //This function completes the reset process and changes the passcode of the user in question
    public function completeresetAction() {
        if ($this->request->isPost()) {
            //Get the info
            $verify = $this->request->getPost('verify-key');
            $email = $this->request->getPost('verify-email');
            $password1 = $this->request->getPost('password1');
            $password2 = $this->request->getPost('password2');
            
            //Check to make sure all field conditions are met
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //email check
                $this->flash->error("This is not an email :(");
                return $this->forward('session/verifyreset/'.$verify);
            }
            if($password1 != $password2 || !$password1) { //password check
                $this->flash->error("Passwords are not the same.");
                return $this->forward('session/verifyreset/'.$verify);
            } 
            if(!$verify) { //verify empty check
                $this->flash->error("Careful, no funny stuff. The Brown University Beekeeping Society is always watching ;)");
                return $this->forward('session/verifyreset/'.$verify);
            }
            //Find a user with the appropriate things. 
            $user = Users::findFirst(
                array( //emails are unique so its fine
                    "(email = :email: AND verify=:verify:)",
                    'bind' => array('email' => addslashes($email), 'verify' => addslashes($verify))
                )
            );
            if(!$user) {
                $this->flash->error("Reset password failed: Invalid email or link. Try again or contact support.");
                return $this->forward('session/tryreset');
            } else if ((time() - $user->verify_timer) > 600) {
                $this->flash->error("Reset password failed: Expired.");
                return $this->forward('session/tryreset');
            } else {
                if(!$user->setPassword($password1)) {
                    $this->flash->error('Fatal Hashing Error. Contact a dev.');
                    return $this->forward('session/completereset');
                }
                $this->flash->success('Success! Please log in.');
            }
            
        }
        return $this->forward('session/index');
    }
}
