
{{ content() }}

{{ stylesheet_link('css/scrolling-nav.css') }}
{{ assets.outputCss('css') }}
{#This generates all the panels#}

{% for panel in elements.getMainPanels() %}
	
	<section style="height:100%;">
    	{{partial(panel)}}
    </section>
    
{% endfor %}


