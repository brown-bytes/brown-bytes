<h2>Offer Summary</h2>

	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <td>Sender</td>
	            <td>Recipient</td>
	            <td>Date</td>
	            <td>Total</td>
	            <td>Status</td>
	            <td>Info</td> 
	        </tr>
	    </thead>
		<tbody>
		    {% for tran in transactions %}
		    	
		        <tr>
		            <td>51001</td>
		            <td>Friðrik Þór Friðriksson</td>
		            <td>2014-04-02</td>
		            <td align="right">12.50</td>
		            <td><span class="label label-success">Success</span></td>
		        </tr>
		    {% endfor %}
	    </tbody>
	</table>



{{link_to("dashboard/index", "Back", "class": "btn btn-primary") }}


{{ content() }}