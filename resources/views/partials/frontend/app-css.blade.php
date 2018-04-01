<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

@if($websiteDetail['font'] && array_key_exists($websiteDetail['font'] , config('wot.font_files')))
  <!-- Font CSS -->
  <link rel="stylesheet" href="{{ config('wot.font_files')[$websiteDetail['font']] }}">
@endif
