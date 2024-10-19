@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <form method="post" action="{{ route('worksite.area.edit') }}">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <label for="">Area Name</label>
                                <input type="text" class="form-control" name="area_name" value="{{ $Areas->Area_Name }}">
                            </div>
                            <div class="col-3">
                                <label for="">Orin Device ID</label>
                                <input type="text" class="form-control" name="Orin_Device_ID"
                                    value=" {{ $Areas->Orin_Device_ID }} ">
                            </div>
                            <div class="col-3">
                                <label for="">Orin_Device_KEY</label>
                                <input type="text" class="form-control" name="Orin_Device_Key"
                                    value=" {{ $Areas->Orin_Device_Key }} ">
                            </div>
                            <div class="col-3">
                                <label for="">Edit Area</label></br>
                                <input type="hidden" class="form-control" name="area_id" value="{{ $Areas->id }}">
                                <input type="submit" class="btn btn-danger w-100" name="submit" value="Edit">
                            </div>

                        </div>



                    </form>


                    <div class="mt-2 area-boxes overflow-hidden overflow-x-auto">
                        <ul>


                        </ul>
                    </div>
                    <div class="hr-line"></div>
                    <div class="main-natification-table">
                        <h2 style="margin-left:15px;margin-top:15px;margin-bottom:15px">Workers</h2>
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th colspan="6">Name</th>
                                    <th colspan="6">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                @if ($AreaUsers)
                                    @foreach ($AreaUsers as $AreaUser)
                                        <tr>
                                            <td colspan="6">{{ $AreaUser->UName }}</td>
                                            <td colspan="6"><a href="/worksite/area/user/remove/{{ $AreaUser->ARUID }}"
                                                    class="btn btn-danger">Remove</a></td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                        <div class="main_loadmore-btn">

                            <button class="load-more" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Add More Users
                            </button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('worksite.area.user') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">USERS</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" value="{{$Areas->id }}" name="AreaID">
                                                <div class="row">
                                                    <ul style="list-style:none;columns:2">
                                                        @if ($ALLUSERS)
                                                            @foreach ($ALLUSERS as $ALLUSER)
                                                                <li>
                                                                    <input type="checkbox" name="users[]"
                                                                        value="{{ $ALLUSER->UID }}">
                                                                    <label for="">{{ $ALLUSER->name }}</label>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>

                                            </div>
                                            <div class="modal-footer">

                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



@endsection
