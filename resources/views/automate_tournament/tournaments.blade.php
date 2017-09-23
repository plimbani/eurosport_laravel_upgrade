<html lang="en">
<head>
  <title>Euro-Sportring</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">
              <h3><u>Published Tournaments</u></h3>
            </div>
            @foreach($publishedTournaments as $tournament)
              <div class="col-md-12">
                <a href="/api/match/automateMatchScheduleAndResult/{{ $tournament->id }}">{{ $tournament->name }}</a>
              </div>
            @endforeach
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">
              <h3><u>Unpublished Tournaments</u></h3>
            </div>
            @foreach($unpublishedTournaments as $tournament)
              <div class="col-md-12">
                <a href="/api/match/automateMatchScheduleAndResult/{{ $tournament->id }}">{{ $tournament->name }}</a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>