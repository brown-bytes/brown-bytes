<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
<h1>Free Food Calendar</h1>
Below is a list of events collected from Events@Brown and handpicked by cool students. Make sure to verify the type of food available. 
<h3>Actions</h3>
{% if login %}
	{{link_to("calendar/new", "Create Event", "class": "btn btn-primary btn-success")}}
	{% if admin %}
		{{link_to("scraper", "Scrape", "class": "btn btn-primary btn-success")}}
	{% endif %}
    <br/>
{% else %}
	<b>You are not logged in. Log in to add events to the calendar.</b>
{% endif %}
<hr/>
<div id='calendar'> 
	{% if offers %}
		<h3>Right Now</h3>
		{% for offer in offers %}
			<div class="panel panel-primary" style="margin-left:15px; ">
				<div class="panel-heading">
					<h3 class="panel-title">{{offer.title}}</h3>
					<div class="row">
						<div class="col-md-11">
							{{offer.location_name}}<br/>
							Expires in: {{offer.expiration}}<br/>
						</div>
						<div class="col-md-1" align="right">
							{{link_to("/offer/index/"~ offer.offer_id, "Details", "class": "btn btn-default")}}
						</div>
					</div>
				</div>
			</div>
		{% endfor %}
	{% endif %}	

	{% if events|length != 0 %}
		{% set daystring = 0 %}
		{% for event in events %}
			{# This handles the headers #}
			{% if event.date_int == daystring %}
				{# This is if the day header has already been posted #}
			{% else %}
				{# This is if the day header needs to be posted (different) #}
				{% set daystring = event.date_int %}
				<div class="day_header">
					<h3> {# reformat THIS NEEDS TO BE DONE! #}
						{{ date('l, F j', event.time_start) }}
					</h3>
				</div>
			{% endif %}
			

			<div class="panel panel-primary" style="margin-left:15px; ">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-10">
							<h3 class="panel-title">
								{{ event.title }}
							</h3>
							<p class="panel-subtitle">
								{{ date('g:i A', event.time_start - 14400) }}
								
								{% if event.time_end %}
									 - 
									{{ date('g:i A', event.time_end - 14400) }}
								{% endif %}
							</p>
							<p class="panel-text">
								{{ event.location }}
								{% if admin %}
									</p>
									<p>
									<b>{{event.user_id}}</b>
								{% endif %}
							</p>
						</div>
						<div class="col-md-2">
							<div class="btn-group pull-right">
								<a target="_blank" href="{{ event.addToGCal() }}" class="button btn btn-primary" title="Add to Google Calendar"><span class="glyphicon glyphicon-calendar"></span></a>
 	 							<a target="_blank" href="{{ event.link }}" class="button btn btn-primary" title="See More Event Info"><span class="glyphicon glyphicon-info-sign"></span></a>
 	 							{% if admin %}
 	 								{% if event.visible %}
 	 									<a href={{ "/calendar/hide/" ~ event.id }} class="button btn btn-danger" title="Hide"><span class="glyphicon glyphicon-remove"></span></a>
 	 								{% else %}
 	 									<a href={{ "/calendar/show/" ~ event.id }} class="button btn btn-success" title="Hide"><span class="glyphicon glyphicon-ok"></span></a>
 	 								{% endif %}
 	 							{% endif %}
								{#{link_to(event.link, "<span class='glyphicon glyphicon-calendar'></span>", "class": "btn btn-primary", "local":false, "target": "blank")}#}
								{# <a target="_blank" href="{{event.link}}" class="card-link">Details</a>#}
							</div>
						</div>
					</div>
				</div>
			</div>
		{% endfor %}
	{% else %}
		<b>That's weird, there are no events with food coming up. Contact the team if this issue keeps occuring.</b>
	{% endif %}
</div>




{{ content() }}