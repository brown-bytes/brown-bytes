<h2>Recent Activity</h2>




<div class="row">
    <div class="col-lg-6" align="left">
        <h3>Active Offers:</h3>
        {% if offers|length is not 0 %}
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td>Location</td>
                    <td>Expiration</td>
                    <td>Comments</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                {% for offer in offers %}
                    <tr>
                        <td>{{offer.location}}</td>
                        <td>{{offer.expiration}}</td>
                        <td>{{offer.activity}}</td>
                        <td align='left'>
                            {{link_to("offer/index/" ~ offer.offer_id, "View", "class": "btn btn-primary btn-xs") }}
                        </td>
                    </tr>
                {% endfor %} 
            </tbody>
        </table>
        {% else %}
        <p><i>No active offers</i></p>
        {% endif %}
    </div>  
    
    <div class="col-lg-6" align="left">
        <h3>Transactions:</h3>
        <p><i>coming soon...</i></p>
    </div>
</div>