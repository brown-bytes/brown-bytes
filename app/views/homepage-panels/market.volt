<section id="market">
    <div class="container">
        <div class="row">
          	<div class="col-lg-8 mx-auto">
	            <h2>Whats Happening?</h2>
	        </div>
	        <div style="float:left">
	            <span>
	            	<table id="market-snapshot">
	           			{% for offer in market.getSnapshot() %}
    						{{offer}}
    					{% endfor %}
	            	</table>
	            </span>
          	</div>
        </div>
    </div>
</section>