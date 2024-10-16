@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>{{ $WORKSITE->Name }} -- {{ $WORKSITE->Start_Date }} -- {{ $WORKSITE->End_Date }}</h5>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create Areas</button>
                    </div>
                    <div class="mt-5 area-boxes overflow-hidden overflow-x-auto">
                        <ul>
                            @if ($Areas)
                                @foreach ($Areas as $area)
                                    <li style="margin-right:15px">
                                        <div class="area">{{ $area->Area_Name }}</div>
                                        <ul>
                                            <li><button type="button" onclick="nextpage({{ $area->id  }})"><i class="fa-solid fa-pen-to-square"> </i></button>
                                            </li>
                                            <li><button><i class="fa-solid fa-trash"></i></button></li>
                                        </ul>
                                    </li>
                                @endforeach
                            @endif

                        </ul>
                        <script>
                            function nextpage(id){
                                window.location.href = window.location.href+"/"+id
                            }
                        </script>
                    </div>
                    <div class="hr-line"></div>
                    <div class="main-natification-table">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Risk Level</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Image/Video</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 6; $i++)
                                    <tr>
                                        <td>01/24/2024</td>
                                        <td>No Helmet</td>
                                        <td>Low</td>
                                        <td>Area {{ $i + 1 }}</td>
                                        <td><button><i class="fa-solid fa-eye"></i></button></td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                        <div class="main_loadmore-btn">
                            <button class="load-more">
                                Load More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- modal  --}}

    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Area</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createArea">
                        <div class="flex-input">
                            <label for="Image">Area Name:</label>
                            <input type="text" name="area_name" placeholder="Area Name">
                            <input type="hidden" name="WSID" value="{{ $WORKSITE->id }}">
                        </div>
                        <div class="flex-input">
                            <label for="Image">Orin Device ID:</label>
                            <input type="text" name="O_D_ID" placeholder="Orin Device ID ">
                        </div>
                        <div class="flex-input">
                            <label for="Image">Orin Device Key: </label>
                            <input type="text" name="O_D_KEY" placeholder="Orin Device Key ">
                        </div>
                        <div class="flex-input">
                            <label for="Image">Device Status:</label>
                            <div class="device-status">
                                <span class="green connected"><i class="fa-solid fa-check"></i> Connected</span>/
                                <span class="red disconnected"><i class="fa-solid fa-xmark"></i> Not Connected</span>
                            </div>
                        </div>
                        {{-- <div class="assign-user-pop">
                        <button  type="button" class="assign-user" data-bs-toggle="modal" data-bs-target="#exampleModal1">Assign Users </button>
                    </div> --}}
                        <div class="main_creat-btn">
                            <button type="submit">Assign Users</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- end modal  --}}


    {{-- user assign modal  --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#createArea').on('submit', (e) => {
                e.preventDefault();
                let form = document.getElementById(
                'createArea'); // Make sure this is an actual form element
                let formData = new FormData(form);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('worksite.area') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.Code === 200) {
                            alert(response.Message); // Capture image ID
                            $("#AreaID").val(response.AID);
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


            $('#assignuser').on('submit', (e) => {
                e.preventDefault();
                let form = document.getElementById(
                'assignuser'); // Make sure this is an actual form element
                let formData = new FormData(form);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('worksite.area.user') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.Code === 200) {
                            alert(response.Message); // Capture image ID

                        }
                    },
                    error: function(response) {
                        alert("Error ! : " + response.Message);
                    }
                });
            });
        });
    </script>
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog assing-userss">
            <div class="modal-content assing-userss">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel1">Assign Users </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="assignusers" action="{{ route('worksite.area.user') }}" method="POST">
                        @csrf
                        <input type="hidden" name="AreaID" value="" id="AreaID" />
                        <div class="main-checkboxx">
                            <h2>UN LISTED USERS</h2>
                            @if ($USERS)
                                @foreach ($USERS as $USER)
                                    <ul style="columns: 4; -webkit-columns: 2; -moz-columns: 2;">
                                        <li>

                                            <input type="checkbox" name="users[]" value="{{ $USER->id }}">
                                            <label for="users">{{ $USER->name }}</label>
                                        </li>
                                    </ul>
                                @endforeach
                            @endif


                        </div>
                        <div class="main_creat-btn">
                            <button type="submit">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- end of user assign modal  --}}




@endsection
