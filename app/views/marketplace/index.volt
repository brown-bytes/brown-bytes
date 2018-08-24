
{% if login %}
	<b>You are currently logged in as <u>{{user}}</u>.</b><br/><br/>
	{{link_to("offer/new", "New Offer", "class": "btn btn-primary btn-success")}}
    <br/><br/>
{% else %}
	<b>You are not logged in. Log in to create and manage offers. Maybe some day you'll be rewarded for your generosity. ;)</b>

{% endif %}






<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <td>Description</td>
            <td>Location</td>
            <td>Offered By</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        {% for offer in offers %}
            <tr>
                <td>{{offer.title}}</td>
                <td>{{offer.location_name}}</td>
                {% if offer.is_owner %}
                    <td><span class="label label-success">You</span></td>
                    <td align='left'>
                        {{link_to("offer/index/" ~ offer.offer_id, "View", "class": "btn btn-primary btn-xs") }}
                        {{link_to("offer/deactivate/" ~ offer.offer_id, "Deactivate", "class": "btn btn-primary btn-xs") }}
                        {{link_to("offer/edit/" ~ offer.offer_id, "Edit", "class": "btn btn-primary btn-xs") }}
                        {#{{link_to("share/offer/" ~ offer.offer_id, "Share", "class": "btn btn-primary btn-xs") }}#}
                    </td>
                {% elseif offer.anonymous %}
                    <td><span class="label label-failed">Anonymous</span></td>
                    <td align='left'>
                        {{link_to("offer/index/" ~ offer.offer_id, "View", "class": "btn btn-primary btn-xs") }}
                    </td>

                {% else %}
                	<td><span class="label-default label">{{offer.name}}</span></td>
                	<td align='left'>
                		{{link_to("offer/index/" ~ offer.offer_id, "View", "class": "btn btn-primary btn-xs") }}
                	</td>
                {% endif %}   
            </tr>
        {% endfor %} 
    </tbody>
</table>

