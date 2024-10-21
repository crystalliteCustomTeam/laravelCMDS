@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>Users</h5>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create New</button>
                    </div>
                    <div class="main-natification-table">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (Count($USER_DATA) > 0)
                                    @php
                                        $user_count = 0;
                                    @endphp
                                    @foreach ($USER_DATA as $user)
                                        <tr>
                                            <td>{{ ++$user_count }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->role == 1)
                                                    Safety Manager
                                                @else
                                                    Worker
                                                @endif
                                            </td>
                                            <td><button onclick="editPage({{ $user->UID }})" class="edit mr-2"><i
                                                        class="fa-solid fa-pen-to-square"></i></button>
                                                <button onclick="deleteuser({{ $user->UID }})" class="delete"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6"> NO User Found</td>
                                    </tr>

                                @endif


                            </tbody>
                        </table>
                        <script>
                            function editPage(id) {
                                window.location.href = window.location + '/edituser/' + id;
                            }

                            function deleteuser(id) {
                                window.location.href = window.location + '/delete/' + id;
                            }
                        </script>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    <form id="imageUploadForm" enctype="multipart/form-data">
                        <div class="flex-input">
                            <label for="Image">Image:</label>
                            <input type="hidden" name="FeaturedImage" value="" id="FeaturedImage" />
                            <img src="" id="FeaturedImageSRC" width="150px" height="150px" style="display: none" />
                            <button type="button" id="FeaturedImageBTN" data-bs-toggle="modal"
                                data-bs-target="#exampleModal1" type="button">Select Images </button>
                        </div>
                        <div class="flex-input">
                            <div id="progressBarContainer" style="display:none;">
                                <div id="progressBar" style="background-color:green; width:0%; height:20px;">
                                </div>
                            </div>
                            <div id="uploadSuccess" style="display:none;">Image uploaded successfully!</div>
                        </div>

                        <div class="flex-input">
                            <label for="Image">Name:</label>
                            <input required type="text" name="name" placeholder=" Name">
                        </div>
                        <div class="flex-input">
                            <label for="Image">Email:</label>
                            <input required type="email" name="email" placeholder="Email ">
                        </div>
                        <div class="flex-input">
                            <label for="Image">Password:</label>
                            <input required type="password" name="password" placeholder="Password ">
                        </div>
                        <div class="flex-input">
                            <label for="Image">Role:</label>
                            <div class="device-status new-userss">
                                <span><input required type="radio" name="role" value="1"> <label
                                        for="safety Manager">safety Manager</label></span>
                                <span><input required type="radio" name="role" value="2"> <label
                                        for="safety Manager">Worker</label></span>
                            </div>
                        </div>
                        <div class="main_creat-btn">
                            <button type="submit">Create</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- end modal  --}}


    {{-- user Gallary  --}}


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#imageUploadForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('image.upload') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        window.location.href = window.location.href;

                    },
                    error: function(response) {
                        console.log(response);
                        alert('Image upload failed.');
                    }
                });
            });
        });
    </script>


    {{-- end of Gallary modal  --}}



    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
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
                                        <button type="submit">Upload Files</button>
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
                                                        <li class="work-site-item" style="width:28.33%"
                                                            onclick="selectImage('{{ $Image->image_path }}')">
                                                            <div class="work-site-box work-site-box-{{ $Image->id }}">
                                                                <div class="work-site-img">
                                                                    <img src="{{ asset($Image->image_path) }}"
                                                                        alt="">
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

    <script>
        function selectImage(imagePath) {
            $("#FeaturedImage").val(imagePath);
            let ImageURL = window.location.origin + "/" + imagePath;
            $("#FeaturedImageSRC").attr('src', ImageURL);
            $("#FeaturedImageSRC").show();
            $('.modal-backdrop').hide();
            const exampleModal1 = bootstrap.Modal.getInstance(document.getElementById('exampleModal1'));
            exampleModal1.hide();
            const exampleModal = document.getElementById('exampleModal');
            exampleModal.classList.add('show');
            exampleModal.style.display = 'block';
            $('.modal-backdrop').show();
        }
    </script>

@endsection
