<html lang="en">
<head>
  <title>Euro-Sportring</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
  <body>
    <div class="container">
      <div class="row">
        <br><br>
        <div class="col-md-12">
          @if($status == 'success')
            <h3 class="text-success">Match has been scheduled and result has been inserted successfully.</h3>
          @endif
          @if($status == 'error')
            <h3 class="text-danger">Error while running.</h3>
          @endif
        </div>
        <br><br><br><br><br>
        <div class="col-md-12">
          <a href="/api/match/automateMatchScheduleAndResult">Go to tournament list</a>
        </div>
        <br><br>
        <div class="col-md-12">
          <a href="/api/match/automateMatchScheduleAndResult/{{ request()->route()->parameters['tournamentId'] }}">Go to competetion list</a>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>