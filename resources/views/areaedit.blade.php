@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <form>
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
                                    <th scope="col">Date</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Risk Level</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Image/Video</th>
                                </tr>
                            </thead>
                            <tbody>


                                @if ($AreaUsers)
                                    @foreach ($AreaUsers as $AreaUser)
                                    <tr>
                                        <td>{{ $AreaUser->UName}}</td>
                                        <td>No Helmet</td>
                                        <td>Low</td>
                                        <td>Area {{ $i + 1 }}</td>
                                        <td><button><i class="fa-solid fa-eye"></i></button></td>
                                    </tr>
                                    @endforeach
                                @endif
                                @for ($i = 0; $i < 6; $i++)
                                    
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


                    {{-- end of user assign modal  --}}




                @endsection
