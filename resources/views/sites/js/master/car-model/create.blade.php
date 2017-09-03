<script>
	function formatMoney(elem) {
        var n = parseInt(elem.val().replace(/\D/g, ''), 10);

        if (isNaN(n)) {
            elem.val('0');
        } else {
            elem.val(n.toLocaleString());
        }
    }
</script>