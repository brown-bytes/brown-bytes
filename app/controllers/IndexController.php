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
    	$message = '
    	<div class="alert alert-danger" role="alert">
			<h4 class="alert-heading">Hey!</h4>
		  	<p>I know you love free food, thanks for using my platform to find it. You may have noticed Brown Bytes was offline over the weekend. Unfortunately the free trial on my hosting has expired, so now I am paying all of the hosting fees out of pocket. It costs me on average 1$/day, which is depleting my bank account! I need ideas for what to do!</p>
		  	<hr>
		  	<p class="mb-0"><u>Please</u> let me know through the <a href="contact/" class="alert-link">contact</a> page if you have any suggestions.</p>
		</div>';
		echo $message;
    }
}
