<script src="{{asset('lte')}}/bower_components/jquery-ui/ui/datepicker.js"></script>
<script>
  $(function () {
    $('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd'
    });
  });

    function formatMoney(elem) {
        var n = parseInt(elem.val().replace(/\D/g, ''), 10);

        if (isNaN(n)) {
            elem.val('0');
        } else {
            elem.val(n.toLocaleString());
        }
    }

    function toMoney(num) {
        return num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }

    function addMonth() {
    	var tr = $('#rate-details').children().html();
    	$('#rate-details').append('<tr>'+tr+'</tr>');
    }

    function removeMonth(elem) {
    	var tr = elem.closest('tr');
    	tr.remove();
    }
</script>