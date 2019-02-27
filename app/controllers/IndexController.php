<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Welcome');
        parent::initialize();
    }

    public function indexAction()
    {
    	/*$message = '
    	<div class="alert alert-danger" role="alert">
			<h4 class="alert-heading">Hey!</h4>
		  	<p>I know you love free food, thanks for using my platform to find it. You may have noticed Brown Bytes was offline over the weekend. Unfortunately the free trial on my hosting has expired, so now I am paying all of the hosting fees out of pocket. It costs me on average 1$/day, which is depleting my bank account! I need ideas for what to do!</p>
		  	<hr>
		  	<p class="mb-0"><u>Please</u> let me know through the <a href="contact/" class="alert-link">contact</a> page if you have any suggestions.</p>
		</div>';*/
        $message = '
        <div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4 class="alert-heading">Good news everyone!</h4>
            <p>You may have read my previous message here. Pretty much, now that I\'m paying for all the hosting fees out of pocket, I want to streamline the service to make it more affordable for me. Luckily I\'ve been able to bring the $1/day cost down to $0.15/day. I\'m still looking for alternate funding though. </p>
            <hr>
            <p class="mb-0"><u>Please</u> let me know through the <a href="contact/" class="alert-link">contact</a> page if you have any suggestions for features.</p>
        </div>
        ';
		//echo $message;
    }
}
