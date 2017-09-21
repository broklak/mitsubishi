<script src="{{asset('lte')}}/bower_components/jquery-ui/ui/datepicker.js"></script>
<script>
  $(function () {
    $('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        maxDate: '0'
    });

    $('#alltime').click(function(){
    	if(this.checked) {
    		$('#duration').hide();
    		$('#alltimespan').show();
    	} else {
    		$('#duration').show();
    		$('#alltimespan').hide();
    	}
    });
  });
</script>