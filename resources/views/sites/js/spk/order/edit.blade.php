<script src="{{asset('lte')}}/bower_components/jquery-ui/ui/datepicker.js"></script>
<script>
  $(function () {
    $('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd'
    });

    $('input[name="payment_method"]').click(function(){
    	if($(this).val() == '1'){
    		$('#leasing-container').hide();	
    	} else {
    		$('#leasing-container').show();
    	}
    	
    });

    $('input[name="price_type"]').click(function(){
        if($(this).val() == '1'){
            $('#oftr-cont').show(); 
            $('#ontr-cont').hide(); 
        } else {
            $('#ontr-cont').show(); 
            $('#oftr-cont').hide(); 
        }
        $('#price_type').val($(this).val());
    });

    $('#dp_amount').keyup(function(){
        calculateDPPercent();
    });

    $('#dp_percentage').keyup(function(){
        calculateDPAmount();
    });

    $('#price_off, #discount, #price_on, #cost_surat').keyup(function(){
        calculateTotalSales();
    });

  });

    function calculateDPPercent() {
        $('#dp_percentage').val('0');
        var total_sale = parseInt($('#total_sales_price').val().replace(/,/gi, ''));
        var val = parseInt($('#dp_amount').val().replace(/,/gi, ''));
        var percent =(isNaN(total_sale)) ? 0 : Math.round((val / total_sale) * 100);
        $('#dp_percentage').val(percent);
    }

    function calculateDPAmount() {
        $('#dp_amount').val('0');
        var total_sale = parseInt($('#total_sales_price').val().replace(/,/gi, ''));
        var val = parseInt($('#dp_percentage').val());
        var amount =(isNaN(total_sale) || isNaN(val)) ? 0 : Math.round((val * total_sale) / 100);
        $('#dp_amount').val(toMoney(amount));
    }

    function formatMoney(elem) {
        var n = parseInt(elem.val().replace(/\D/g, ''), 10);

        if (isNaN(n)) {
            elem.val('0');
        } else {
            elem.val(n.toLocaleString());
        }
    }

    function calculateTotalSales() {
        $('#total_sales_price').val('0');
        var price_off = parseInt($('#price_off').val().replace(/,/gi, ''));
        var price_on = parseInt($('#price_on').val().replace(/,/gi, ''));
        var cost_surat = parseInt($('#cost_surat').val().replace(/,/gi, ''));
        var discount = ($('#discount').val()) ? parseInt($('#discount').val().replace(/,/gi, '')) : 0;
        var type = $('#price_type').val();

        if(type == '1') {
            var total = (isNaN(price_off)) ? 0 : price_off - discount;
        } else {
            var total = (isNaN(price_on) || isNaN(cost_surat)) ? 0 :  price_on + cost_surat - discount;
        }

        $('#total_sales_price').val(toMoney(total));

        if(total > 0) {
            calculateDPAmount();
            calculateDPPercent();
        }
    }

    function toMoney(num) {
        return num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
</script>