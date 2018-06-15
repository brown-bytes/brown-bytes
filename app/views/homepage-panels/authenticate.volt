<section id="authenticate">
    <div class="container">
        <div class="row">
          	<div class="col-lg-8 mx-auto text-center">
	            <h2>Log In or Sign Up</h2>
	           	<h3>To make announcements and much more, we need to know you actually go to Brown<h3>
	            <div class="row">
	            	{{ link_to('session', 'Login', 'class': 'btn btn-primary btn-large btn-success') }}
	            	{{ link_to('register', 'Sign Up', 'class': 'btn btn-primary btn-large btn-success') }}
	            </div>
          	</div>
        </div>
    </div>
</section>