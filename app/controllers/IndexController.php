<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Welcome - Brown Bytes: Free Food For All');
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
        /*$message = '
        <div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4 class="alert-heading">Good news everyone!</h4>
            <p>You may have read my previous message here. Pretty much, now that I\'m paying for all the hosting fees out of pocket, I want to streamline the service to make it more affordable for me. Luckily I\'ve been able to bring the $1/day cost down to $0.15/day. I\'m still looking for alternate funding though. </p>
            <hr>
            <p class="mb-0"><u>Please</u> let me know through the <a href="contact/" class="alert-link">contact</a> page if you have any suggestions for features.</p>
        </div>
        ';*/
        /*$message = '<div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4 class="alert-heading">Warning!</h4>
            <p>For the next week Brown Bytes will be under development. There are few free food events for Spring Break, so I will be building in more features to make your life easier.</p>
            <hr>
            <p class="mb-0"><u>Please</u> let me know through the <a href="contact/" class="alert-link">contact</a> page if you have any suggestions for features.</p>
        </div>';*/
        /*$message = '<div class="alert alert-info alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4 class="alert-heading">Whoops!</h4>
            <p>You might have noticed our <a href="/register" class="alert-link">sign-up</a> page just straight-up wasn\'t working for the last 2 months. Well now it is! Sorry everyone, I now have error messaging set up so I\'ll get an email everytime something breaks.</p>
            <hr>
            <p class="mb-0"><u>Please</u> let me know through the <a href="/contact" class="alert-link">contact</a> page if you have any suggestions for features or complaints.</p>
        </div>';*/
        $message = '<div class="alert alert-info alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h3 class="alert-heading">Thank You!</h3>
            <p>Thank you so much for helping Brown Bytes make Brown a better place. For the <b>summer</b> the website will be live, but I will not be curating, so feel free to post using the <a href="/calendar/new" class="alert-link">new event</a> button on the calendar.</p>
            <hr>
            <p class="mb-0"><u>Please</u> let me know through the <a href="/contact" class="alert-link">contact</a> page if you have any suggestions for features or complaints.</p>
        </div>';
		echo $message;
    }
}
