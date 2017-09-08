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
        clearInterestFormula();
    });

    $('#type_id').change(function() {
        clearInterestFormula();
    });

    $('#car_year').keyup(function() {
        clearInterestFormula();
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
        clearInterestFormula();
    });

    $('#dp_amount').keyup(function(){
        calculateDPPercent();
        clearInterestFormula();
    });

    $('#dp_percentage').keyup(function(){
        calculateDPAmount();
        clearInterestFormula();
    });

    $('#price_off, #discount, #price_on, #cost_surat').keyup(function(){
        calculateTotalSales();
    });

    $('#admin_cost, #insurance_cost, #other_cost').keyup(function(){
        calculateTotalDP();
    });

    $('#interest_rate').keyup(function(){
        calculateInstallment($(this));
    });

    $('#leasing_id').change(function(){
        var val = $(this).val();
        var cost = $('#admin_cost_leasing_'+val).val();
        $('#admin_cost').val(toMoney(cost));
    });

    $('#leasing_id, #credit_duration').change(function() {
        getInterestRate();
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

    function getInterestRate() {
        var dp_percentage = $('#dp_percentage').val();
        var dp_amount = $('#dp_amount').val();
        var leasing_id = $('#leasing_id').val();
        var duration = $('#credit_duration').val();
        var admin_cost = $('#admin_cost').val();
        var other_cost = $('#other_cost').val();
        var type_id = $('#type_id').val();
        var dealer_id = $('#dealer_id').val();
        var karoseri = $('#karoseri_price').val();
        var car_year = $('#car_year').val();
        var unpaid = $('#total_unpaid').val();
        var total_sales_price = $('#total_sales_price').val();

        if(dp_percentage != undefined && leasing_id != undefined && duration != undefined && type_id != undefined) {
            $.ajax({
                method: 'GET',
                url: '{{route('ajax.getLeasingFormula')}}',
                data: {'dp':dp_percentage, 'leasing':leasing_id, 'duration':duration, 'car_type':type_id, 'karoseri':karoseri, 'dealer':dealer_id, 'unpaid':unpaid, 'total_sales':total_sales_price, 'car_year': car_year},
                success: function(result) {
                    obj = JSON.parse(result);
                    $('#interest_rate').val(toMoney(obj.interest));
                    $('#installment_cost').val(toMoney(obj.installment));
                    $('#insurance_cost').val(toMoney(obj.insurance));
                    calculateTotalDP();
                }
            });
        }
    }

    function calculateInstallment(interest) {
        var rate = parseFloat(interest.val());
        var month = parseInt($('#credit_duration').val());
        var year = Math.floor(month / 12);
        var unpaid = parseInt(toInt($('#total_unpaid').val()));

        console.log(rate);
        console.log(month);
        console.log(year);
        console.log(unpaid);

        var totalInterest = (rate / 100 * unpaid) * year;
        var unpaidAndInterest = unpaid + totalInterest;
        var installment = Math.floor(unpaidAndInterest / month);

        $('#installment_cost').val(toMoney(installment));

        calculateTotalDP();
    }

    function calculateTotalDP() {
        var dp_amount = toInt($('#dp_amount').val());
        var admin_cost = toInt($('#admin_cost').val());
        var other_cost = toInt($('#other_cost').val());
        var installment = toInt($('#installment_cost').val());
        var insurance = toInt($('#insurance_cost').val());

        var total = parseInt(dp_amount) + parseInt(installment) + parseInt(admin_cost) + parseInt(other_cost) + parseInt(insurance);
        $('#total_down_payment').val(toMoney(total));
    }

    function clearInterestFormula() {
        $('#leasing_id').val('');
        $('#credit_duration').val('0');
        $('#interest_rate').val('0');
        $('#installment_cost').val('0');
        $('#insurance_cost').val('0');
        $('#admin_cost').val('0');
        $('#other_cost').val('0');
        $('#total_down_payment').val('0');
    }
</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "name", "customer_phone" )
          .addClass( "custom-combobox-input form-control" )
          .autocomplete({
            delay: 0,
            minLength: 7,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            classes: {
              "ui-tooltip": "ui-state-highlight"
            }
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            // SELECT HANDLER
            var val = ui.item.option.value;

            // POPULATE
            $('#customer_name').val($('#firstname-'+val).val());
            $('#customer_last_name').val($('#lastname-'+val).val());
            $('#id_number').val($('#idnumber-'+val).val());
            $('#address').val($('#address-'+val).val());
            $('#customer_npwp').val($('#npwp-'+val).val());
            $("input[name='id_type'][value='"+$('#idtype-'+val).val()+"']").prop('checked', 'checked');
            $('#id_image').attr('src', $('#idimage-'+val).val()).show();

            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a style='display:none'>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .on( "mousedown", function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .on( "click", function() {
            input.trigger( "focus" );
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
            return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        // this.input
        //   .val( "" )
        //   .attr( "title", value + " didn't match any item" )
        //   .tooltip( "open" );
        // this.element.val( "" );
        // this._delay(function() {
        //   this.input.tooltip( "close" ).attr( "title", "" );
        // }, 2500 );
        // this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
 
    $( "#combobox" ).combobox();

    $('#type_id').autocomplete({
        source: "{{route('ajax.getCarType')}}",
        minLength: 3,
        select: function( event, data) {
            $('#type_id_real').val(data.item.id);
        }
    });
  } );
  </script>