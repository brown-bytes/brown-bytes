<h2>My Offers</h2>

<div class="text-left" style="margin-bottom:10px;">
    {{link_to("offer/new", "New Offer", "class": "btn btn-primary btn-success")}}
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <td>Description</td>
            <td>Location</td>
            <td>Date</td>
            <td>Status</td>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
        {% for offer in offers %}
            <tr>
                <td>{{offer.title}}</td>
                <td>{{offer.location}}</td>
                <td>{{offer.date}}</td>
                {% if offer.status %}
                    <td><span class="label label-success">Active</span></td>
                    <td align='left'>
                        {{link_to("offer/index/" ~ offer.offer_id, "View", "class": "btn btn-primary btn-xs") }}
                        {{link_to("offer/deactivate/" ~ offer.offer_id, "Deactivate", "class": "btn btn-primary btn-xs") }}
                        {{link_to("offer/edit/" ~ offer.offer_id, "Edit", "class": "btn btn-primary btn-xs") }}
                        {{link_to("share/offer/" ~ offer.offer_id, "Share", "class": "btn btn-primary btn-xs") }}
                    </td>
                {% else %}
                    <td><span class="label label-danger">Not Active</span></td>
                    <td align='left'>
                        {{link_to("offer/index/" ~ offer.offer_id, "View", "class": "btn btn-primary btn-xs") }}
                        {{link_to("offer/activate/" ~ offer.offer_id, "Activate", "class": "btn btn-primary btn-xs") }}
                        {{link_to("offer/edit/" ~ offer.offer_id, "Edit", "class": "btn btn-primary btn-xs") }}
                    </td>
                {% endif %}

                
            </tr>
        {% endfor %} 
    </tbody>
</table>