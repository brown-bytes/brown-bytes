
{{ content() }}

{{ stylesheet_link('css/scrolling-nav.css') }}

{#This generates all the panels#}
{% for panel in elements.getMainPanels() %}
    {{partial(panel)}}
{% endfor %}


