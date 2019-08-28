
{{ content() }}

{{ stylesheet_link('css/scrolling-nav.css') }}
{{ assets.outputCss('css') }}
{#This generates all the panels#}

{% for index, panel in elements.getMainPanels() %}
	<section 
		{{ panel['padding'] is defined ? 'style="padding: ' ~ panel['padding'] ~ ';"' : ''}}
	>
    	{{partial(panel['path'])}}
    </section>
    
{% endfor %}


