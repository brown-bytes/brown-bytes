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
            //printf("Started saving<br/>");

            $name = $this->request->getPost('name', array('string', 'striptags'));
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if ($password != $repeatPassword) {
                $this->flash->error('Passwords are different');
                return false;
            }
            //printf("Trying Users<br/>");
            $user = new Users();

            $user->name = $name;
            $user->email = $email;

            //uniqid() for verification
            $verify = uniqid();
            $user->verify = $verify;

            //printf("After Users<br/>");
            if(!$user->setPassword($password)) {
                $this->flash->error('Fatal Hashing Error. Contact a dev.');
                return $this->forward('register/index');
            }
            //printf("Set Password");
            
            
            require(APP_PATH . 'app/validators.php');

            $validator = new RegisterValidator();
            $messages = $validator->validate($user);
            if (count($messages)) {
                $this->flash->setAutoescape(false);
                foreach ($messages as $messagy) {
                    $this->flash->error((string) $messagy);
                }
                unset($user);
            } else {  
                //printf("Got to saving there is a problem");
                if ($user->save() == false) {
                    foreach ($user->getMessages() as $message) {
                        $this->flash->error((string) $message);
                    }
                } else {
                    $this->tag->setDefault('email', 'bru.no@brown.edu');
                    $this->tag->setDefault('password', '');
                    //$this->sendVerify($verify, $name, $email);
                    $this->flash->success('Thank you for signing up, a verification link has been emailed to you.');
                    $notice = new Mailer('scott@huson.com', 'Someone Registered', 'Name: '.$user->name.'<br/>Email: '.$user->email.'<br/>END.');
                    $email = new Mailer($user->email, 'Verify Your Email', 'Hi '.$user->name.',<br/><br/>Thank you for registering on Brown Bytes. We are happy to have you join the team.<br/>To complete verification, click the link below.<br/>http://brownbytes.org/session/verify/'.$user->verify.'<br/><br/>Best,<br/>The Brown Bytes Team');
                    return $this->forward('session/index');
                }
            }
        }

        $this->view->form = $form;
    }
    //DOES NOT WORK, MAIL() FUNCTION IS NOT WORKING.
    //USE EXAMPLE ABOVE IN REGISTER
    public function sendVerify($uniq, $name, $email) {
        $content = "Dear ".$name.",<br/><br/>Thank you for signing up for Brown Bytes! This is a verification email to verify that your veriable identity has been verified. Click the link below to confirm that your created this account.<br/><a target='_blank' href='http://www.brown-bytes.org/session/verify/".$uniq."'>Check me out, im a link!</a><br/><br/>If you didn't create this account, consider creating one for real at 'www.brownbytes.org'.<br/>If you would like to contact the Brown Bytes gods, go to <a target='_blank' href='http://www.brown-bytes.org/contact'>the Contact page</a> on the Brown Bytes website.<br/><br/>Thank you for your time,<br/>B. B. g.<br/><i>Brown Bytes god</i>";

        mail($email, 'Brown Bytes Account verification', $content);
    }
}
