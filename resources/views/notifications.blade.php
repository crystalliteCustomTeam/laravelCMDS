@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>Communication</h5>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create
                            New</button>
                    </div>
                    <div class="main-natification-table">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Notification</th>
                                    <th scope="col">Work Site</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($AllNotification)
                                    @php
                                        $index = 1;
                                    @endphp
                                    @foreach ($AllNotification as $Notification)
                                        <tr>
                                            <td>{{ $index++ }}</td>
                                            <td>{{ $Notification->title }}</td>
                                            <td>
                                                @if ($Notification->WSID != 0)
                                                    @php
                                                        $jsonDatas = json_decode($Notification->WSID);
                                                    @endphp
                                                    @foreach ($jsonDatas as $JD)
                                                        @foreach ($WORKSITE as $WK)
                                                            @if ($WK->id != $JD)
                                                                @continue
                                                            @else
                                                                <button style="width: fit-content;color:white;padding:0px 10px">{{ $WK->Name }}</button>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @continue
                                                @endif
                                            </td>
                                            <td>
                                                @if ($Notification->ARIDS != 0)
                                                    @php
                                                        $jsonDatas = json_decode($Notification->ARIDS);
                                                    @endphp
                                                    @foreach ($jsonDatas as $JD)
                                                        @foreach ($AREAS as $WK)
                                                            @if ($WK->id != $JD)
                                                                @continue
                                                            @else
                                                                <button style="width: fit-content;color:white;padding:0px 10px">{{ $WK->Area_Name }}</button>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @continue
                                                @endif
                                            </td>
                                            <td>
                                                <button class="delete" onclick="deleteNotification({{ $Notification->id }})"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif



                            </tbody>
                        </table>
                        <div class="main_loadmore-btn">
                            {{-- <button class="load-more">
                                Load More
                            </button> --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- modal  --}}
    <script>
        function deleteNotification(ID){
            window.location.href  = window.location.href+'/delete/'+ID;
        }
    </script>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create New</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="notificationFORM">
                        <div class="main_flex">
                            <label for="Image">Title</label>
                            <input required type="text" name="title" placeholder="Title">
                        </div>
                        <div class="main_flex">
                            <label for="Image">Message</label>
                            <textarea required name="message" placeholder="Message"></textarea>
                        </div>

                        <div class="main_creat-btn">
                            <button type="submit">Create</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#notificationFORM').on('submit', (e) => {
                e.preventDefault();

                let formData1 = new FormData(document.getElementById("notificationFORM"));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('notifications.create') }}",
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.Code === 200) {


                            $("#notficationID").val(response.NID);
                            let modal = new bootstrap.Modal(document.getElementById(
                                'exampleModal1'));
                            modal.show();
                        }
                    },
                    error: function(response) {
                        alert("Error ! : " + response.Message);
                    }
                });
            });


            $('#notficationAssign').on('submit', (e) => {
                e.preventDefault();

                let formData1 = new FormData(document.getElementById("notficationAssign"));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('notifications.send') }}",
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.Code === 200) {


                            alert("NOTIFICATION SEND");
                            window.location.reload();
                        }
                    },
                    error: function(response) {
                        alert("Error ! : " + response.Message);
                    }
                });
            });


        });
    </script>

    {{-- end modal  --}}


    {{-- user assign modal  --}}

    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog assing-userss">
            <div class="modal-content assing-userss">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel1">Worksite + Area</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('notifications.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="notificationID" id="notficationID" value="" />
                        <div class="side-roll">
                            <input type="checkbox">
                            <label for="">Select All</label>
                        </div>
                        <div class="main-checkboxx child-workside"
                            style="overflow: hidden;height:500px;    overflow-y: scroll;
">
                            <ul>
                                @if ($WORKSITE)
                                    @foreach ($WORKSITE as $WS)
                                        <li>
                                            <input type="checkbox" id="worksite-{{ $WS->id }}" name="worksiteID[]"
                                                value="{{ $WS->id }} ">
                                            <label for="">{{ $WS->Name }}</label>
                                            <ul>
                                                @if ($AREAS)
                                                    @foreach ($AREAS as $AR)
                                                        @if ($AR->WSID == $WS->id)
                                                            <li>
                                                                <input id="areasite-{{ $WS->id }}" type="checkbox"
                                                                    name="areas[]" value="{{ $AR->id }}">
                                                                <label for="">{{ $AR->Area_Name }}</label>
                                                            </li>
                                                        @else
                                                            @continue
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </ul>
                                        </li>
                                    @endforeach
                                @endif


                            </ul>


                        </div>
                        <div class="main_creat-btn mt-3">
                            <button type="submit">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- end of user assign modal  --}}

@endsection
