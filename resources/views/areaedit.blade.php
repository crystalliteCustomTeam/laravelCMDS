@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)


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
                                <input type="submit" class="btn btn-danger w-100" name="submit" value="Save">
                            </div>

                        </div>



                    </form>


                    <div class="mt-2 area-boxes overflow-hidden overflow-x-auto">
                        <ul>


                        </ul>
                    </div>
                    <div class="hr-line"></div>
                    <div class="main-natification-table">
                        <h2 style="margin-left:15px;margin-top:15px;margin-bottom:15px">Safety Manager + Workers</h2>
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
                                            <td colspan="6"><a href="javascript:void(0);" class="btn btn-danger remove-user-btn" onclick="removeUser({{ $AreaUser->ARUID }})">Remove</a>
                                            </td>
                                            {{--<td colspan="6">
                                                <button class="btn btn-danger remove-user-btn" data-id="{{ $AreaUser->ARUID }}" data-bs-toggle="modal" data-bs-target="#deleteAreaModal">Remove</button>
                                            </td>--}}
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

    <div class="modal fade" id="deleteAreaModal" tabindex="-1" aria-labelledby="deleteAreaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAreaModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this area?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmAreaDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('worksite.area.user') }}"]');

            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(form);
                const url = form.action;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.Code === 200) {
                            toastr.success(data.Message);
                            // Optionally, refresh the page or close the modal
                            setTimeout(() => {
                                location.reload(); // Refresh the page
                            }, 2000);
                        } else {
                            toastr.error(data.Message);
                        }
                    })
                    .catch(error => {
                        toastr.error('An unexpected error occurred. Please try again later.');
                        console.error('Error:', error);
                    });
            });
        });


        let areaIdToDelete = null; // Store the ID of the area to delete

        function removeUser(id) {
            areaIdToDelete = id; // Set the ID of the area to delete

            // Show the modal
            const deleteAreaModal = new bootstrap.Modal(document.getElementById('deleteAreaModal'));
            deleteAreaModal.show();
        }

        document.getElementById('confirmAreaDeleteBtn').addEventListener('click', function () {
            if (areaIdToDelete) {
                window.location.href = `/worksite/area/user/remove/${areaIdToDelete}`;
            }

            const deleteAreaModal = bootstrap.Modal.getInstance(document.getElementById('deleteAreaModal'));
            deleteAreaModal.hide();
        });

    </script>

@endsection
