@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)




@section('contents')

    <section class="work-site mt-2">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="main_bar_graph">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="main_risk">
                        <h4>MOST RISK DETECTED SITES</h4>
                        <div class="main-risk-table">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Work Sites</th>
                                        <th scope="col">Risks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($RISKS)
                                        @foreach ($RISKS as $RISK)
                                            <tr>
                                                <td>{{ $RISK->Name }}</td>
                                                <td>{{ $RISK->alerts_count }}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    <div class="main_counts-listing">
                        <ul>
                            <li>
                                <img src="{{ asset('assets/images/dash-users.png') }}" alt="">
                                <h5>Users</h5>
                                <p>{{ $USERCOUNT }}</p>
                            </li>
                            <li>
                                <img src="{{ asset('assets/images/dash-work.png') }}" alt="">
                                <h5>Work Sites</h5>
                                <p>{{ $WORKSITE_COUNT }}</p>
                            </li>
                            <li>
                                <img src="{{ asset('assets/images/dash-alarm.png') }}" alt="">
                                <h5>Notifications</h5>
                                <p>{{ $NotificationCount }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
