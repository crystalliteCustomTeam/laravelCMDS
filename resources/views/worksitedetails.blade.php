@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">

                <div class="col-12">
                    <div class="row">

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="first-top-headerrr " style="width:100% !important;display:inline-block">
                        <form action="{{ route('worksite.edit') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="siteId" value="{{ $WORKSITE->id }}">
                                    <input type="hidden" name="FeaturedImage" value="{{ asset($WORKSITE->FeaturedImage) }}"
                                        id="FeaturedImage" />
                                    <img src="{{ asset($WORKSITE->FeaturedImage) }}" id="FeaturedImageSRC" width="100px"
                                        height="100px" style="width: 150px;height: 150px; border-radius: 10%; margin-bottom: 10px; object-fit: cover;" />
                                    <button type="button" id="FeaturedImageBTN" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal3" type="button">Select Images </button>
                                </div>
                                <div class="col">
                                    <label for="">Site Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $WORKSITE->Name }}">
                                </div>
                                <div class="col">
                                    <label for="">Site Description</label>
                                    <input type="text" class="form-control" name="description"
                                        value="{{ $WORKSITE->Description }}">
                                </div>
                                <div class="col">
                                    <label for="">Start Date</label>
                                    <input type="date" class="form-control" name="startDate"
                                        value="{{ $WORKSITE->Start_Date }}">

                                </div>
                                <div class="col">
                                    <label for="">End Date</label>
                                    <input type="date" class="form-control" name="enddate"
                                        value="{{ $WORKSITE->End_Date }}">

                                </div>
                                <div class="col-12 mt-3" style="display: flex;justify-content: flex-end;">
                                    <input type="submit" value="Edit"
                                        style="background: #14173A;border-radius: 10px;color: white;padding: 7px 20px 7px 20px;border: 0;margin-right:5px">
                                    <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create
                                        Areas</button>
                                </div>
                            </div>
                        </form>



                    </div>
                    <div class="mt-5 area-boxes overflow-hidden overflow-x-auto" style="margin-top:2% !important">
                        <h5>Areas</h5>
                        <ul>
                            @if ($Areas)
                                @foreach ($Areas as $area)
                                    <li style="margin-right:15px;margin-bottom:15px">
                                        <div class="area">{{ $area->Area_Name }}</div>
                                        <ul>
                                            <li><button type="button" onclick="nextpage({{ $area->id }})"><i
                                                        class="fa-solid fa-pen-to-square"> </i></button>
                                            </li>
                                            <li><button type="button" onclick="deletearea({{ $area->id }})"><i
                                                        class="fa-solid fa-trash"></i></button></li>
                                        </ul>
                                    </li>
                                @endforeach
                            @endif

                        </ul>
                        <script>
                            function nextpage(id) {
                                window.location.href = window.location.href + "/" + id
                            }

                            function deletearea(id) {

                                window.location.href = window.location.href + "/delete/" + id
                            }
                        </script>
                    </div>
                    <div class="hr-line"></div>
                    <div class="main-natification-table">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Alert Code</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Risk Level</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Image/Video</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($frontalert)
                                    @foreach ($frontalert as $fal)
                                        <tr>
                                            <td>{{ $fal->created_at }}</td>
                                            <td>{{ $fal->alert_code }}</td>
                                            <td>{{ $fal->description }}</td>
                                            <td>{{ $fal->risk_level }}</td>
                                            <td>Area {{ $fal->area_code}}</td>
                                            <td>
                                                <a href="{{ $fal->captured_image_url }}" target="_blank">
                                                    <svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="white" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                        <script>
                            function viewpage(src){
                                window.location.href = src;
                            }
                        </script>
                        <div class="main_loadmore-btn">

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

                                            <input type="checkbox" name="users[]" value="{{ $USER->UID }}">
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


    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog assing-userss">
            <div class="modal-content assing-userss">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel1">Gallary </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="main_tabing">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home-tab-pane" type="button" role="tab"
                                    aria-controls="home-tab-pane" aria-selected="false">Upload</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile-tab-pane" type="button" role="tab"
                                    aria-controls="profile-tab-pane" aria-selected="true">Media</button>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade " id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                                tabindex="0">
                                <div class="main-upload">
                                    <h5>Upload an image / Video </h5>
                                    <form id="imageuploadtab">
                                        <input type="file" id="fileInput" accept="image/*" multiple>
                                        <button type="submit" style="background: #14173A;
    border-radius: 10px;
    color: white;
    padding: 7px 20px 7px 20px;
    border: 0;
    margin: 0 15px 0 0;">Upload Files</button>
                                    </form>
                                    <script>
                                        document.getElementById('imageuploadtab').addEventListener('submit', function(event) {
                                            event.preventDefault();
                                            let formData = new FormData();
                                            let files = document.getElementById('fileInput').files;

                                            for (let i = 0; i < files.length; i++) {
                                                formData.append('files[]', files[i]);
                                            }

                                            fetch('/upload/tab/image', {
                                                    method: 'POST',
                                                    body: formData,
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                                            'content')
                                                    }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    let workSiteList = document.getElementById('work-site-list');

                                                    // Loop through each uploaded image and append it to the list
                                                    data.uploadedImages.forEach(image => {
                                                        let newListItem = document.createElement('li');
                                                        newListItem.className = 'work-site-item';
                                                        newListItem.style.width = '28.33%';
                                                        newListItem.setAttribute('onclick', `selectImage('${image.image_path}')`);

                                                        newListItem.innerHTML = `
                                                        <div class="work-site-box work-site-box-${image.id}">
                                                            <div class="work-site-img">
                                                                <img src="${image.image_path}" alt="">
                                                            </div>
                                                        </div>
                                                    `;

                                                        workSiteList.appendChild(newListItem);
                                                    });

                                                    // After successful file upload, open the Media tab
                                                    document.getElementById('profile-tab').click();
                                                })
                                                .catch(error => {
                                                    console.error(error);
                                                    // Handle error
                                                });
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel"
                                aria-labelledby="profile-tab" tabindex="0">
                                <div class="mediaaa">
                                    <form action="">
                                        <div class="media-selection-page">
                                            <ul id="work-site-list">
                                                @if ($Images)
                                                    @foreach ($Images as $Image)
                                                        <li class="work-site-item"
                                                            onclick="selectImage('{{ $Image->image_path }}')">
                                                            <div class="work-site-box work-site-box-{{ $Image->id }}">
                                                                <div class="work-site-img">
                                                                    <img src="{{ asset($Image->image_path) }}"
                                                                        alt="">
                                                                </div>
                                                                <div class="work-side-content mb-0">
                                                                    <h6>{{ $Image->image_title }}</h6>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif

                                            </ul>
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

    {{-- end of Gallary modal  --}}
    <script>
        function selectImage(imagePath) {
            $("#FeaturedImage").val(imagePath);
            let ImageURL = window.location.origin + "/" + imagePath;
            $("#FeaturedImageSRC").attr('src', ImageURL);
            $("#FeaturedImageSRC").show();
            $('.modal-backdrop').hide();
            const exampleModal1 = bootstrap.Modal.getInstance(document.getElementById('exampleModal3'));
            exampleModal1.hide();

        }
    </script>

@endsection
