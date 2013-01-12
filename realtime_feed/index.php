

<!DOCTYPE html>
<html>
  <head>
    <title>Bootstrap 101 Template</title>
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.dark.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <h1 style="text-align: center">P.I.M.P. REAL-TIME FEED</h1>
    <script src="/js/jquery-latest.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <div class="row-fluid">
      <div class="span8 offset2">
        <table id="feed" class="table .table-striped">
          <tr>
            <th>Time</th>
            <th>Name</th>
            <th>Bling Transaction</th>
            <th>Reason</th>
          </tr>
        </table>
      </div>
      </div>
  </body>
  <script>
  //ajax requests to periodically fetch tick data from server
  window.last = null;
  setInterval(function(){
    $.get('server.php', function(data){
      //check the table if it already has this piece of data. if not, append it to the top of the row.
      console.log(data);
      if (window.last !== data.time) {
        if (data.points > 50) {
          var img = "/img/down_red_arrow.png";
        } else {
          var img = "/img/up_green_arrow.png";
        }
        var formatted_time = data.time
        $('#feed').append('<tr><td>' + formatted_time + '</td><td>' + data.first_name + ' ' + data.last_name + '</td><td><img src="'+ img + '"</td><td>' + data.points + '</td><td>' + data.reason +'</td></tr>');
        window.last = data.time;
      }
    })
  },1000);
  </script>


</html>
