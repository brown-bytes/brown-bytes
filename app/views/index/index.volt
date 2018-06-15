
{{ content() }}



{#This generates all the panels#}
{% for panel in elements.getMainPanels() %}
    {{partial(panel)}}
{% endfor %}


