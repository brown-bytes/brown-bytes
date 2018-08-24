<h2>Edit Offer</h2>

{{ form("offer/edited/"~offer_id) }}

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
        {{ submit_button("Submit", "class": "btn btn-success") }}
    </ul>

</form>

<p>Note: Editing your offer does not change the status of the offer.</p>