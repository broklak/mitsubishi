<script src="{{asset('lte')}}/bower_components/jquery-ui/ui/datepicker.js"></script>
<script>
  $(function () {
    $('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd'
    });

    $('#dp_amount').keyup(function(){
        calculateDPPercent();
    });

    $('#dp_percentage').keyup(function(){
        calculateDPAmount();
    });

    $('#leasing_id').change(function(){
        var val = $(this).val();
        var cost = $('#admin_cost_leasing_'+val).val();
        $('#admin_cost').val(toMoney(cost));
    });

  });

    function calculateDPPercent() {
        $('#dp_percentage').val('0');
        var total_sale = parseInt($('#total_sales_price').val().replace(/,/gi, ''));
        var val = parseInt($('#dp_amount').val().replace(/,/gi, ''));
        var percent =(isNaN(total_sale)) ? 0 : Math.round((val / total_sale) * 100);
        $('#dp_percentage').val(percent);
        calculateUnpaid(total_sale, val);
    }

    function calculateDPAmount() {
        $('#dp_amount').val('0');
        var total_sale = parseInt($('#total_sales_price').val().replace(/,/gi, ''));
        var val = parseInt($('#dp_percentage').val());
        var amount =(isNaN(total_sale) || isNaN(val)) ? 0 : Math.round((val * total_sale) / 100);
        $('#dp_amount').val(toMoney(amount));
        calculateUnpaid(total_sale, amount);
        return amount;
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
            var dpAmount = calculateDPAmount();
            calculateDPPercent();
            calculateUnpaid(total, dpAmount);
            calculateTotalDP();
        }
        clearInterestFormula();
    }

    function calculateUnpaid(total, dpAmount) {
        var unpaid = total - dpAmount;
        $('#total_unpaid').val(toMoney(unpaid));
    }

    function toMoney(num) {
        return num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }

    function toInt(money) {
         return Number(money.replace(/[^0-9\.-]+/g,""));
    }

    function getCalculation() {
        var dp_percentage = $('#dp_percentage').val();
        var dp_amount = $('#dp_amount').val();
        var leasing_id = $('#leasing_id').val();
        var duration = $('#credit_duration').val();
        var admin_cost = $('#admin_cost').val();
        var other_cost = $('#other_cost').val(); 
        var type_id = $('#type_id').val();
        var car_year = $('#car_year').val();
        var unpaid = $('#total_unpaid').val();
        var total_sales_price = $('#total_sales_price').val();

        if(dp_percentage != undefined && leasing_id != undefined && duration != undefined && type_id != undefined) {
            $.ajax({
                method: 'GET',
                url: '{{route('ajax.getLeasingFormula')}}',
                data: {'dp':dp_percentage, 'leasing':leasing_id, 'duration':duration, 'car_type':type_id, 'total_sales':total_sales_price, 'unpaid':unpaid, 'car_year': car_year},
                success: function(result) {
                    obj = JSON.parse(result);
                    $('#interest_rate').val(toMoney(obj.interest));
                    $('#installment_cost').val(toMoney(obj.installment));
                    $('#insurance_cost').val(toMoney(obj.insurance));
                    calculateTotalDP();
                    $('#calculation').show();
                }
            });
        } else {
            alert('Please input all fields');
        }
    }

    function calculateTotalDP() {
        var dp_amount = toInt($('#dp_amount').val());
        var admin_cost = toInt($('#admin_cost').val());
        var installment = toInt($('#installment_cost').val());
        var insurance = toInt($('#insurance_cost').val());

        var total = parseInt(dp_amount) + parseInt(installment) + parseInt(admin_cost) + parseInt(insurance);
        $('#total_down_payment').val(toMoney(total));
    }
</script>