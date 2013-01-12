<?php
//get the user data
require_once('user_info.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css">
  <script src="//code.jquery.com/jquery-1.8.2.min.js"></script>
  <script src="//code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header">
    <h1><?php echo $first_name . $last_name;?></h1>
  </div><!-- /header -->
  <div data-role="content">
    <!--form to send data-->
    <form action="saveIntroData.php" method="get" data-ajax="false">
      <?php echo "<label for='intro'>Tell us a little about yourself," . $first_name . ".We will use this for the Mingle game. </label>";?>
      <textarea name="intro" required="required" placeholder="Example: Once I ate so much mochi that it globbed up my intestines and I nearly died."></textarea>
      <input type="checkbox" name="optout" id="optoutcheckbox" class="custom" value="false">
      <label for="optoutcheckbox">I DO NOT want to play Mingle</label>
      <?php echo "<input name='token' type='hidden' value=" . $token . ">";?>
      <?php echo "<input name='pgid' type='hidden' value=" . $pgid . ">"; ?>
      <input type="submit" value="Save">
    </form>
  </div><!-- /content -->
</div><!-- /page -->

</body>
<script>
$(document).ready(function() {
  $('#optoutcheckbox').change(function(){
    var introbox = $('textarea')
    if($(this).is(':checked')){
        introbox.attr('disabled','disabled')
        introbox.removeAttr("required")
      } else {
        introbox.attr('required','required')
        introbox.removeAttr("disabled")
      }
  })
});

</script>

</html>
