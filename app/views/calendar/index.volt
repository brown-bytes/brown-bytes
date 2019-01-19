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
					<h3 class="panel-title">
						{{ event.title }}
					</h3>
					<p class="panel-subtitle">
						{{ date('g:i A', event.time_start) }}
						
						{% if event.time_end %}
							 - 
							{{ date('g:i A', event.time_end) }}
						{% endif %}
					</p>
					<p class="panel-text">
						{{ event.location }}
					</p>
					<a target="_blank" href="{{event.link}}" class="card-link">Details</a>
				</div>
			</div>
		{% endfor %}
	{% else %}
		<b>That's weird, there are no events with food coming up. Contact the team if this issue keeps occuring.</b>
	{% endif %}
</div>




{{ content() }}