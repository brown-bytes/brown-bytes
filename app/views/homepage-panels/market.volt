
<div class="container">
    <div class="row" style="vertical-align: middle;">
    	{% set offers = market.getSnapshot() %}
    	{% if offers|length is not 0 %}
          	<div class="col-lg-4 mx-auto">
	            <h1>Free Food.<br/>Right Now.</h1>
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
				    	{% for offer in offers %}
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
	            {{link_to("calendar/index", "See All", 'class': "btn btn-primary btn-lg text-center") }}
	        </div>
		{% else %}
			<div class="col-lg-6 mx-auto">
	            <h2>Looks like there aren't any offers, create your own offer or check out the calendar for free food events.</h2>
	        </div>
			<div class="col-lg-2 mx-auto" style="display:inline-block;" align="right">
	            {{link_to("offer/new", "New Offer", 'class': "btn btn-primary btn-success btn-lg text-center") }}
	        </div>
	        <div class="col-lg-2 mx-auto" style="display:inline-block;" align="left">
	        	{{link_to("calendar/index", "Calendar", 'class': "btn btn-primary btn-success btn-lg text-center") }}
	        </div>
	       	<div class="col-lg-2 mx-auto" style="display:inline-block;">
	       	</div>

		{% endif %}
    </div>
</div>
