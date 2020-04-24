@extends('presentation.layouts.selection')

@section('title', 'Page Title')

@section('body_class', 'selection')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <div class="tournament-logo">
            <div class="logo-container is-center">
                <img src="//placehold.it/90x90" alt="">
            </div>
        </div>
        <div class="selection-area">
            <div class="selection-desc">
                <p>Select screen size.</p>
            </div>
            <div class="selection-box">
                <div class="radio-tile-group">
                    <div class="input-container">
                      <input id="small-screen" class="radio-button" type="radio" name="radio" />
                      <div class="radio-tile">
                        <div class="icon">
                            <svg fill="#000000" width="124" height="111" viewBox="0 0 124 111" xmlns="http://www.w3.org/2000/svg" >
                                <path d="M113.666667,0 L10.3333333,0 C4.62847222,0 0,4.66113281 0,10.40625 L0,79.78125 C0,85.5263672 4.62847222,90.1875 10.3333333,90.1875 L51.6666667,90.1875 L48.2222222,100.59375 L32.7222222,100.59375 C29.8590278,100.59375 27.5555556,102.913477 27.5555556,105.796875 C27.5555556,108.680273 29.8590278,111 32.7222222,111 L91.2777778,111 C94.1409722,111 96.4444444,108.680273 96.4444444,105.796875 C96.4444444,102.913477 94.1409722,100.59375 91.2777778,100.59375 L75.7777778,100.59375 L72.3333333,90.1875 L113.666667,90.1875 C119.371528,90.1875 124,85.5263672 124,79.78125 L124,10.40625 C124,4.66113281 119.371528,0 113.666667,0 Z M110.222222,76.3125 L13.7777778,76.3125 L13.7777778,13.875 L110.222222,13.875 L110.222222,76.3125 Z"/>
                            </svg>
                        </div>
                        <label for="small-screen" class="radio-tile-label">Small screen</label>
                        <div class="radio-tile-info">
                            Small screens</br>( > 1920x1080)
                        </div>
                      </div>
                    </div>

                    <div class="input-container">
                      <input id="wide-screen" class="radio-button" type="radio" name="radio" />
                      <div class="radio-tile">
                        <div class="icon">
                            <svg fill="#000000" width="124" height="100" viewBox="0 0 124 100" xmlns="http://www.w3.org/2000/svg">
                                <path d="M114.7,0 L9.3,0 L9.29999959,0 C4.16374522,0 -4.06516562e-07,4.16374563 -4.06516562e-07,9.3 C-4.06516562e-07,9.3 -4.06516562e-07,9.3 -4.06516562e-07,9.3 L-4.06516562e-07,71.3 L-4.06516562e-07,71.3000014 C-4.06516562e-07,76.4362558 4.16374522,80.6000014 9.29999959,80.6000014 L57.3499996,80.6000014 L57.3499996,89.9000014 L21.6999996,89.9000014 L21.6999995,89.9000014 C19.987916,89.9000015 18.5999995,91.2879179 18.5999995,93 C18.5999995,93 18.5999995,93 18.5999995,93 L18.5999995,96.1000014 L18.5999995,96.1000019 C18.5999997,97.8120854 19.9879179,99.2000019 21.6999995,99.2000019 L102.299999,99.2000019 L102.299999,99.2000019 C104.012083,99.2000019 105.399999,97.8120854 105.399999,96.1000019 L105.399999,93 L105.399999,93 C105.399999,91.2879184 104.012083,89.9000019 102.299999,89.9000019 L66.6499993,89.9000019 L66.6499993,80.6000019 L114.699999,80.6000019 L114.699999,80.6000019 C119.836253,80.6000021 124,76.4362562 124,71.3000019 L124,9.30000187 L124,9.30000187 C124,4.1637475 119.836253,0 114.699999,0 L114.7,0 Z M114.7,71.3 L9.3,71.3 L9.3,9.3 L114.7,9.3 L114.7,71.3 Z"/>
                            </svg>
                        </div>
                        <label for="wide-screen" class="radio-tile-label">Wide screen</label>
                        <div class="radio-tile-info">
                            Wide screen</br>( < 1920x1080)
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="selection-preview-link">
                {{-- <div class="link-label"><span>Preview link</span></div> --}}
                <div class="link-input-group">
                    <input type="text" class="link-form-control" placeholder="Preview link" aria-label="Preview link" aria-describedby="preview-link" value="https://www.esrtmp.com/schedule_results/" readonly>
                    <div class="link-input-group-append">
                        <button class="btn btn-secondary btn-round-corner" id="preview-link" onclick="window.location.href = '{{ route("presentation.index") }}';">Preview</button>
                    </div>
                </div>
            </div>
            <div class="selection-btn">
                <a href="{{ route("presentation.index") }}" class="btn btn-primary" target="_blank">Continue</a>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
