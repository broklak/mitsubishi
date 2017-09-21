<script src="{{asset('lte')}}/bower_components/raphael/raphael.min.js"></script>
<script src="{{asset('lte')}}/bower_components/morris.js/morris.min.js"></script>

<script type="text/javascript">
	$(function () {
    "use strict";

    //BAR CHART
    $.ajax({
    	method: 'GET',
    	url: '{{route('ajax.getGraphDO')}}',
    	success: function(result) {
		  var bar = new Morris.Bar({
		      element: 'do-chart',
		      resize: true,
		      data: JSON.parse(result),
		      barColors: ['#1e88e5', '#00a65a'],
		      xkey: 'period',
		      ykeys: ['totalSales', 'totalDo'],
		      labels: ['Sales', 'DO'],
		      hideHover: 'auto'
		    });    		
    	}
    });

    //BAR CHART
    $.ajax({
    	method: 'GET',
    	url: '{{route('ajax.getGraphSPK')}}',
    	success: function(result) {
		  var bar = new Morris.Bar({
		      element: 'spk-chart',
		      resize: true,
		      data: JSON.parse(result),
		      barColors: ['#1e88e5', '#00a65a', '#f56954'],
		      xkey: 'label',
		      ykeys: ['processed', 'approved', 'rejected'],
		      labels: ['Processed', 'Approved', 'Rejected'],
		      hideHover: 'auto'
		    });    		
    	}
    });
  });
</script>