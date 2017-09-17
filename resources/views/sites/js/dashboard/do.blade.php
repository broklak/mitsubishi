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



  //   var bar = new Morris.Bar({
  //     element: 'bar-chart',
  //     resize: true,
  //     data: [
  //       {y: '2006', a: 100, b: 90},
  //       {y: '2007', a: 75, b: 65},
  //       {y: '2008', a: 50, b: 40},
  //       {y: '2009', a: 75, b: 65},
  //       {y: '2010', a: 50, b: 40},
  //       {y: '2011', a: 75, b: 65},
  //       {y: '2012', a: 100, b: 90}
  //     ],
  //     barColors: ['#00a65a', '#f56954'],
  //     xkey: 'y',
  //     ykeys: ['a', 'b'],
  //     labels: ['SPK', 'DO'],
  //     hideHover: 'auto'
  //   });
  });
</script>