<h2>New Offer</h2>

{{ form("offer/create") }}
    Offers are a way for you to publicize swipes or points that you would like to give away. <br/>
    Post an offer for some swipes at a location and it will appear above all the free food events in the calendar. <br/><br/>
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