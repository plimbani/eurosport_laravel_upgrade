@extends('tvpresentation.layouts.default')

@section('title', 'Page Title')

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
                    {{-- <div class="date-lable">Date</div> --}}
                    <div class="date">U11/7 (U11/7)</div>
                </div>
                <div class="middle-area">
                    {{-- <div class="title">Matches</div> --}}
                </div>
                <div class="right-area">
                    <div>
                        <span class="active-page">1</span> of 2
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="page-header">
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
        </div> --}}
        <div class="page-body">
            <div class="group-layout grid-group-layout">
                @for ($i = 0; $i < 4; $i++)
                <div class="group-grid-item">
                    <div class="group-table-info">
                        <div class="group-table-label">Group</div>
                        <div class="group-table-name">U1{{ $i }}/{{ $i + 3 }}-U{{ $i }}1/{{ $i + 2 }}-Group-A</div>
                    </div>
                    <table class="group-table">
                        <thead>
                            <tr>
                                <th class="teams">Teams</th>
                                <th class="played">Played</th>
                                <th class="difference">Difference</th>
                                <th class="points">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @for ($i = 0; $i < 7; $i++) --}}
                            <tr>
                                <td class="teams">
                                    <span class="team-section">
                                        <span class="flag-area">
                                            <span class="flag-icon flag-icon-ax"></span>
                                        </span>
                                        <span class="team-name">Beith Juniors Community Football Club</span>
                                    </span>
                                </td>
                                <td class="played">0</td>
                                <td class="difference">0</td>
                                <td class="points">0</td>
                            </tr>
                            <tr>
                                <td class="teams">
                                    <span class="team-section">
                                        <span class="flag-area">
                                            <span class="flag-icon flag-icon-be"></span>
                                        </span>
                                        <span class="team-name">Beith Juniors Community Football Club</span>
                                    </span>
                                </td>
                                <td class="played">0</td>
                                <td class="difference">0</td>
                                <td class="points">0</td>
                            </tr>
                            <tr>
                                <td class="teams">
                                    <span class="team-section">
                                        <span class="flag-area">
                                            <span class="flag-icon flag-icon-gb-eng"></span>
                                        </span>
                                        <span class="team-name">Beith Juniors Community Football Club</span>
                                    </span>
                                </td>
                                <td class="played">0</td>
                                <td class="difference">0</td>
                                <td class="points">0</td>
                            </tr>
                            <tr>
                                <td class="teams">
                                    <span class="team-section">
                                        <span class="flag-area">
                                            <span class="flag-icon flag-icon-gb-nir"></span>
                                        </span>
                                        <span class="team-name">Beith Juniors Community Football Club</span>
                                    </span>
                                </td>
                                <td class="played">0</td>
                                <td class="difference">0</td>
                                <td class="points">0</td>
                            </tr>
                            <tr>
                                <td class="teams">
                                    <span class="team-section">
                                        <span class="flag-area">
                                            <span class="flag-icon flag-icon-sm"></span>
                                        </span>
                                        <span class="team-name">Beith Juniors Community Football Club</span>
                                    </span>
                                </td>
                                <td class="played">0</td>
                                <td class="difference">0</td>
                                <td class="points">0</td>
                            </tr>
                            <tr>
                                <td class="teams">
                                    <span class="team-section">
                                        <span class="flag-area">
                                            <span class="flag-icon flag-icon-gb-sct"></span>
                                        </span>
                                        <span class="team-name">Beith Juniors Community Football Club</span>
                                    </span>
                                </td>
                                <td class="played">0</td>
                                <td class="difference">0</td>
                                <td class="points">0</td>
                            </tr>
                            <tr>
                                <td class="teams">
                                    <span class="team-section">
                                        <span class="flag-area">
                                            <span class="flag-icon flag-icon-gb"></span>
                                        </span>
                                        <span class="team-name">Beith Juniors Community Football Club</span>
                                    </span>
                                </td>
                                <td class="played">0</td>
                                <td class="difference">0</td>
                                <td class="points">0</td>
                            </tr>
                            {{-- @endfor --}}
                        </tbody>
                    </table>
                </div>
                @endfor
            </div>
        </div>
        <div class="page-footer"></div>
    <!-- END Page Content -->
@endsection
