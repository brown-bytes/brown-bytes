<h2>New Offer</h2>

{{ form("offer/create") }}

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