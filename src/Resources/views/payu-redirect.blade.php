<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payu Payment Gateway Redirection.......</title>
</head>

<body>
    <center>
        <h1>Please do not refresh this page redirecting to gateway site...</h1>
    </center>
    <form action="<?php echo $action; ?>" method="post" name="payuForm" id="payuForm">
        <input name="surl" id="surl" type="hidden" value="{{ $posted['surl']}}" />
        <input name="furl" id="furl" type="hidden" value="{{ $posted['furl'] }}" />
        <input name="curl" id="curl" type="hidden" value="{{ $posted['curl'] }}" />
        <input type="hidden" name="key" value="{{ $posted['key'] }}" />
        <input type="hidden" name="txnid" value="{{ $posted['txnid'] }}" />
        <input name="amount" type="hidden" value="{{ $posted['amount'] }}" />
        <input type="hidden" name="productinfo" value="{{ $posted['productinfo'] }}" />
        <input name="firstname" id="firstname" type="hidden" value="{{ $posted['firstname']}}" />
        <input name="email" id="email" type="hidden" value="{{ $posted['email']}}" />
        <input name="phone" value="{{ $posted['phone']}}" type="hidden" />
        <input type="hidden" name="hash" value="{{ $hash }}" />
    </form>
    <script>
        document.payuForm.submit();
    </script>
</body>

</html>