<section id="market">
    <div class="container">
        <div class="row" style="vertical-align: middle;">
          	<div class="col-lg-4 mx-auto">
	            <h2>Whats Happening?</h2>
	        </div>
			<div class="col-lg-6 mx-auto">
				<table class="table table-bordered table-striped whats-happening-table"> 
					<thead>
				        <tr>
				            <td width="50%">Description</td>
				            <td>Location</td>
				            <td>Expires (hours)</td>
				        </tr>
				    </thead>
				    <tbody>
				    	{% for offer in market.getSnapshot() %}
							<tr>
								<td>{{offer.title}}</td>
								<td>{{offer.location_name}}</td>
								<td>{{offer.expiration}}</td>
							</tr>
						{% endfor %}
					</tbody>
				</table>
			</div>
			<div class="col-lg-2 mx-auto" style="display:inline-block;">
	            {{link_to("marketplace/index", "See All", 'class': "btn btn-primary btn-lg text-center") }}
	        </div>
	            	
        </div>
    </div>
</section>