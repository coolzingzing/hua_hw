<h1>Hello</h1>

<?php

$orderid='01_'. uniqid();
?>
<form action="https://ccore.spgateway.com/MPG/mpg_gateway" method="post" id="PaymentForm">

    <input type="hidden" name="MerchantID" value="<?php echo $this->merchantID; ?>">
    <input type="hidden" name="RespondType" value="String">
    <input type="hidden" name="CheckValue" value="<?php echo $this->checkValue; ?>">
    <input type="hidden" name="TimeStamp" value="<?php echo $this->timeStamp ; ?>">
    <input type="hidden" name="Version" value="<?php echo $this->version ; ?>">
    <input type="hidden" name="LangType" value="zh-tw">
    <input type="hidden" name="MerchantOrderNo" value="<?php echo $this->orderId; ?>">
    <input type="hidden" name="Amt" value="<?php  echo $this->amount ; ?>">
    <input type="hidden" name="ItemDesc" value="hello">
    <input type="hidden" name="ReturnURL" value="<?php echo JUri::root(). 'index.php?option=com_bliss&task=spReturn'; ?>">
    <input type="hidden" name="NotifyURL" value="<?php echo JUri::root(). 'index.php?option=com_bliss&task=spNotify'; ?>">
    <input type="hidden" name="CustomerURL" value="<?php echo JUri::root(). 'index.php?option=com_bliss&task=spCustomer'; ?>">
    <input type="hidden" name="Email" value="yc.tu@blisswisdom.org">
    <input type="hidden" name="LoginType" value="0">

    <input type="hidden" name="CREDIT" value="1">
    <input type="hidden" name="WEBATM" value="1">
    <input type="hidden" name="VACC" value="1">
    <input type="hidden" name="CVS" value="1">

    <button class="btn btn-primary">Pay</button>


</form>
