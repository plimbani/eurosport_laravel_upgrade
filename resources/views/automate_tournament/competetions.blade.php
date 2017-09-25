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
          <a href="/api/match/automateMatchScheduleAndResult">Back to tournament list</a>
        </div>
        <div class="col-md-12">
          <br><br>
          <div class="text-danger"><strong>Note: On click of "Schedule Match & Insert Result", it will unschedule all the matches of the tournament AND then it will schedule and insert match result for only of the selected competetion</strong></div>
          <div class="row">
              <div class="col-md-12">
                <h3><u>Competitions</u></h3>
              </div>
              @foreach($tournamentCompetationTemplates as $tct)
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-3">
                      {{ $tct->group_name }} ( {{ $tct->category_age }} )
                    </div>
                    <div class="col-md-6">
                      <a href="/api/match/automateMatchScheduleAndResult/{{ request()->route()->parameters['tournamentId'] }}/{{ $tct->id }}">Schedule Match & Insert Result</a>
                    </div>
                  </div>
                </div>
              @endforeach
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      // function scheduleMatchAndInsertResult(tournamentId, ageCategoryId) {
      //   $.ajax({
      //     url: "/api/match/automateMatchScheduleAndResult/" + tournamentId + "/" + ageCategoryId,
      //     type: 'POST',
      //     success: function(result){
            
      //     }
      //     error: function(result){
            
      //     }
      //   });
      // }
    </script>
  </body>
</html>