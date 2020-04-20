@extends('tvpresentation.layouts.default')

@section('title', 'Match')

@section('body_class', 'presentation')

{{-- @section('sidebar')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection --}}

@section('content')
    <!-- Page Content -->
        <div class="page-header">
            <div class="page-container">
                <div class="left-area">
                    <div class="date-lable">Date</div>
                    <div class="date">20th Mar 2020</div>
                </div>
                <div class="middle-area">
                    <div class="title">Matches</div>
                </div>
                <div class="right-area">
                    <div>
                        <span class="active-page">1</span> of 2
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <table class="match-table">
                <thead>
                    <tr>
                        <th class="time">Time</th>
                        <th class="group">Group</th>
                        <th class="code">Code</th>
                        <th class="placing">Placing</th>
                        <th class="venue">Venue</th>
                        <th class="matches">Matches</th>
                        <th class="score">Score</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 17; $i++)
                    <tr>
                        <td class="time">{{$i}}:00</td>
                        <td class="group">U1{{ $i }}/{{ $i + 3 }}-U{{ $i }}1/{{ $i + 2 }}-Group-A</td>
                        <td class="code">1.{{ $i }}</td>
                        <td class="placing">{{$i*2}}-{{$i*4}}</td>
                        <td class="venue">Soccerland Catanlunya (Top Ten) (Spain)</td>
                        <td class="matches">
                            <span class="team">
                                <span class="team-info">
                                    <span class="team-name">
                                        Beith Juniors Community Football Club
                                    </span>
                                    <span class="team-kit">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="39" height="36" viewBox="0 0 39 36">
                                            <defs>
                                                <polygon id="dress-a" points="0 0 0 13 9.394 13 10.5 9.454 11.605 13 21 13 21 0" />
                                                <path id="dress-c" d="M38.2956412,6.38084457 L30.7278358,0 L22.9259746,0 C22.3378343,1.38654985 20.9473026,2.36108957 19.3251156,2.36108957 C17.7023284,2.36108957 16.3117967,1.38654985 15.7242566,0 L7.92239536,0 L0.353989848,6.38084457 C-0.0961175287,6.7586189 -0.120123256,7.43743216 0.299976963,7.85062283 L4.32153634,11.8054479 L7.92239536,9.4443583 L7.92239536,23.6108957 L30.7278358,23.6108957 L30.7278358,9.4443583 L34.3286948,11.8054479 L38.349654,7.85062283 C38.7697543,7.43743216 38.7457485,6.7586189 38.2956412,6.38084457" />
                                            </defs>
                                            <g fill="none" fill-rule="evenodd">
                                                <g transform="translate(9 23)">
                                                    <mask id="dress-b" fill="#fff">
                                                        <use xlink:href="#dress-a" />
                                                    </mask>
                                                    <use fill="#EDEDED" xlink:href="#dress-a" />
                                                    <g fill="#46237A" mask="url(#dress-b)">
                                                        <rect width="21" height="13" />
                                                    </g>
                                                </g>
                                                <mask id="dress-d" fill="#fff">
                                                    <use xlink:href="#dress-c" />
                                                </mask>
                                                <use fill="#000" xlink:href="#dress-c" />
                                                <g fill="#FC7700" mask="url(#dress-d)">
                                                    <rect width="40" height="24" transform="translate(-1)" />
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </span>
                                <span class="team-info">
                                    <span class="team-name">
                                        Beith Juniors Community Football Club
                                    </span>
                                    <span class="team-kit">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="39" height="36" viewBox="0 0 39 36">
                                            <defs>
                                                <polygon id="dress-a" points="0 0 0 13 9.394 13 10.5 9.454 11.605 13 21 13 21 0" />
                                                <path id="dress-c" d="M38.2956412,6.38084457 L30.7278358,0 L22.9259746,0 C22.3378343,1.38654985 20.9473026,2.36108957 19.3251156,2.36108957 C17.7023284,2.36108957 16.3117967,1.38654985 15.7242566,0 L7.92239536,0 L0.353989848,6.38084457 C-0.0961175287,6.7586189 -0.120123256,7.43743216 0.299976963,7.85062283 L4.32153634,11.8054479 L7.92239536,9.4443583 L7.92239536,23.6108957 L30.7278358,23.6108957 L30.7278358,9.4443583 L34.3286948,11.8054479 L38.349654,7.85062283 C38.7697543,7.43743216 38.7457485,6.7586189 38.2956412,6.38084457" />
                                            </defs>
                                            <g fill="none" fill-rule="evenodd">
                                                <g transform="translate(9 23)">
                                                    <mask id="dress-b" fill="#fff">
                                                        <use xlink:href="#dress-a" />
                                                    </mask>
                                                    <use fill="#EDEDED" xlink:href="#dress-a" />
                                                    <g fill="#46237A" mask="url(#dress-b)">
                                                        <rect width="21" height="13" />
                                                    </g>
                                                </g>
                                                <mask id="dress-d" fill="#fff">
                                                    <use xlink:href="#dress-c" />
                                                </mask>
                                                <use fill="#000" xlink:href="#dress-c" />
                                                <g fill="#FC7700" mask="url(#dress-d)">
                                                    <rect width="40" height="24" transform="translate(-1)" />
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </span>
                            </span>
                        </td>
                        <td class="score">{{ $i }}&nbsp;&nbsp;-&nbsp;&nbsp;{{$i+2}}</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <div class="page-footer"></div>
    <!-- END Page Content -->
@endsection
