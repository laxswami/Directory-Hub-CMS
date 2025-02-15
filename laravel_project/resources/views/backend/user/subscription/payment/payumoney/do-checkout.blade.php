<html>
<body onload="submitPayuForm()">
<span id="rzp-button">{{ __('payumoney.pay-redirect-message') }}</span>
<br>
<br>
<form action="{{ $action_url }}" method="POST" name="payuForm">
    <input type="hidden" name="key" value="{{ $merchant_key }}"/>
    <input type="hidden" name="txnid" value="{{ $txnid }}"/>
    <input type="hidden" name="amount" value="{{ $amount }}"/>
    <input type="hidden" name="productinfo" value="{{ $productinfo }}"/>
    <input type="hidden" name="firstname" value="{{ $firstname }}"/>
    <input type="hidden" name="email" value="{{ $email }}"/>
    <input type="hidden" name="phone" value="{{ $phone }}"/>
    <input type="hidden" name="surl" value="{{ $surl }}"/>
    <input type="hidden" name="furl" value="{{ $furl }}"/>
    <input type="hidden" name="hash" value="{{ $hash }}"/>
    <input type="hidden" name="service_provider" value="payu_paisa" size="64"/>
</form>


<script>
    function submitPayuForm() {
        var payuForm = document.forms.payuForm;
        payuForm.submit();
    }
</script>
</body>
</html>
