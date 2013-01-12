<?php
//get the user data
require_once('user_info.php');
require_once('../common.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/jquery.mobile.min.css">
</head>
<body>

<div data-role="page">
  <div data-role="header">
    <h1><?php echo $first_name . " ". $last_name;?></h1>
  </div><!-- /header -->
  <div data-role="content">
    <!--form to send data-->
    <form action="saveIntroData.php" method="get" data-ajax="false">
      <?php echo "<label for='intro'>Welcome to the party!  Give a hint for how others can find you, and Mingle will give you hints on how to find them.  If you both Mingle each other within the same minute, you'll each earn Bling toward fame, glory, and a free Galaxy Nexus 7!</label>";?>
      <textarea name="intro" required="required" placeholder="Example: I am wearing a blue shirt and look like a supermodel"></textarea>

      <input type="checkbox" name="optout" id="optoutcheckbox" class="custom" value="false">
      <label for="optoutcheckbox">Opt out of playing Mingle</label>
      <?php echo "<input name='token' type='hidden' value=" . $token . ">";?>
      <?php echo "<input name='pgid' type='hidden' value=" . $pgid . ">"; ?>
      <input type="submit" value="Save">
    </form>
  </div><!-- /content -->
</div><!-- /page -->

</body>
<script src="/js/jquery-latest.js"></script>
<script src="/js/jquery.mobile-1.2.0.min.js"></script>
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
