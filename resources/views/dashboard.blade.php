@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

@section('contents')

<section class="work-site mt-2">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="main_bar_graph">
                    <canvas id="myChart" ></canvas>
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
                                <tr>
                                    <td>Work site 02</td>
                                    <td>5</td>
                                  </tr>
                                  <tr>
                                    <td>Work site 02</td>
                                    <td>5</td>
                                  </tr>
                                  <tr>
                                    <td>Work site 02</td>
                                    <td>5</td>
                                  </tr>
                                  <tr>
                                    <td>Work site 02</td>
                                    <td>5</td>
                                  </tr>
                                  <tr>
                                    <td>Work site 02</td>
                                    <td>5</td>
                                  </tr>
                            </tbody>
                          </table>
                          
                         
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <div class="main_counts-listing">
                    <ul>
                        <li>
                            <img src="{{asset('assets/images/dash-users.png')}}" alt="">
                            <h5>Users</h5>
                            <p>24</p>
                        </li>
                        <li>
                            <img src="{{asset('assets/images/dash-work.png')}}" alt="">
                            <h5>Work Sites</h5>
                            <p>4</p>
                        </li>
                        <li>
                            <img src="{{asset('assets/images/dash-alarm.png')}}" alt="">
                            <h5>Notifications</h5>
                            <p>50</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
