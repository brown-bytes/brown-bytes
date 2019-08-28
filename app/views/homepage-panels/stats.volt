<div class="container">
    <div class="row">
        {% set stats = market.getEventStats() %}

      	<div class="col-sm-4 mx-auto text-center">
            <div class="column-item">
                <h2 style="padding-top:10px;">Est. Money Saved:</h2>
                <h1 class="stats-font">${{stats['saved']}}</h1>
            </div>
      	</div>
      	<div class="col-sm-4 mx-auto text-center">
            <div class="column-item">
                <h2 style="padding-top:10px;">Free Events Today:</h2>
                <h1 class="stats-font">{{stats['today']}}</h1>
            </div>
      	</div>
        <div class="col-sm-4 mx-auto text-center">
            <div class="column-item">
                <h2 style="padding-top:10px;">Average Daily Events:</h2>
                <h1 class="stats-font">{{stats['average']}}</h1>
            </div>
      	</div>
    </div>
</div>