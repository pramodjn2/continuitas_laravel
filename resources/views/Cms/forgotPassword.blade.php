<?php $random_number = mt_rand(); ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<table style="width:100%; margin:0 auto; background-color:#f1f1f1; font:300 14px Arial, Helvetica, sans-serif; " width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr style="border:1px solid #ccc;">
    <td colspan="3" bgcolor="#FFFFFF">
     <h2>Hi <?=ucwords($result->name);?>,</h2>
       <p>Email:
    <?=$result->email?>
  </p>
  <p>{{trans('common.verificationCode')}}:
    <?=$verified_code?>
  </p>
      <table width="50%" border="0">
  <tr>
   
  </tr>
</table>
<a href="<?=url('login/emailverify?verifycode='.$verified_code)?>"><button class="red_button" type="button"> Forgot password verification </button></a>
      <br/>
      <br/>
	  <p>{{trans('common.copyPastThisUrl')}}:</p>
      <p><?=url('login/emailverify?verifycode='.$verified_code);?></p>
      <p> {{trans('common.cantSeeButtonUselink')}}: <a href="<?=url('login/emailverify?verifycode='.$verified_code)?>">Click here</a> </p>

    
    </td>
  </tr>
</table>
</body></html>