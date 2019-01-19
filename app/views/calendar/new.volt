<h2>New Event</h2>
Before creating a custom event please check that it does not already exist. 
<hr/>
{{ form("calendar/create") }}

    <fieldset>

    {% for element in form %}
        {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
            {{ element }}
        {% else %}
            <div class="form-group">
                {{ element.label() }}
                {{ element.render(['class': 'form-control']) }}
            </div>
        {% endif %}
    {% endfor %}

    </fieldset>

	<ul class="pull-right">
        {{ submit_button("Create", "class": "btn btn-success") }}
    </ul>

</form>