{{ content() }}

<h2>Offer Details</h2>
<div class="details">
<h3>Description:</h3>
<p>{{title}}</p><br/>
<b>Creator:   </b>
{% if is_owner %}
	<span class="label label-success">You</span><br/>
{% else %}
	{{owner}}<br/>
{% endif %}
<b>Expiration Date: </b>
{{expires}}<br/>
<br/>


<br/>
{% if is_owner %}
	<b>You are the creator of this offer.</b><br/>
	{% if is_active %}
		{{link_to("offer/deactivate/" ~ id, "Deactivate", "class": "btn btn-primary") }}
	{% else %}
		{{link_to("offer/activate/" ~ id, "Activate", "class": "btn btn-primary") }}
	{% endif %}
    {{link_to("offer/edit/" ~ id, "Edit", "class": "btn btn-primary") }}
    {#{{link_to("offer/share/" ~ id, "Share", "class": "btn btn-primary") }}#}

{% endif %}
</div>

<h3>Comments:</h3>
<div class="comments">
	{% for comment in comments %}
		{% if comment['owned'] %}
			<div class="well" style="background-color: #d0d0d0; border: 1px solid #d0d0d0; ">
				<b>You:</b>
				<p>{{ comment['content'] }}</p>
			</div>
		{% else %}
			<div class="well well-dg">
				<b>{{ comment['author'] }}:</b>
				<p>{{ comment['content'] }}</p>
			</div>
		{% endif %}
	{% endfor %}
</div>
<div class="add_comment">
	{{ form("offer/comment/" ~ id) }}
		
		<fieldset>

	    {% for element in form %}
	        {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
	            {{ element }}
	        {% elseif is_a(element, 'Phalcon\Forms\Element\Check') %}
	            {{ element.render() }} 
	            {{ element.label() }}
	        {% else %}
	            <div class="form-group">
	                {{ element.label() }}
	                {{ element.render(['class': 'form-control']) }}
	            </div>
	        {% endif %}
	    {% endfor %}

	    </fieldset>

		<ul class="pull-right">
	        {{ submit_button("Comment", "class": "btn btn-success") }}
	    </ul>
	</form>
<div/>  
<br/><br/>

{{link_to("marketplace/index", "Back to Market", "class": "btn btn-primary") }}