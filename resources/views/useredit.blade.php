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
                        <form id="imageUploadForm" enctype="multipart/form-data">
                        <button type="submit">Save</button>
                    </div>
                    <div class="main-natification-table">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
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
                                                <input  type="hidden" name="userID"
                                                        value="{{ $user->UID }}" />
                                                <td><input required type="text" name="name"
                                                        value="{{ $user->name }}" />
                                                </td>
                                                <td> <input required type="email" name="email"
                                                        value="{{ $user->email }}" /></td>
                                                <td>
                                                    @if ($user->role == 1)
                                                        Safety Manager
                                                    @else
                                                        Worker
                                                    @endif
                                                    </br>
                                                    <select name="role" required>

                                                        <option value="1">Safety Manager</option>
                                                        <option value="2">Worker</option>
                                                    </select>

                                                </td>
                                                <td>
                                                    <input type="hidden" name="FeaturedImage" value="{{ asset($user->featuredImage) }}" id="FeaturedImage" />
                                                    <img src="{{ asset($user->featuredImage) }}" id="FeaturedImageSRC" width="200"
                                                        height="200" />
                                                </br></br>
                                                    <button type="button"  style="width:70%;height:40px;color:white" id="FeaturedImageBTN" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal1" type="button">Change Image
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6"> NO User Found</td>
                                        </tr>

                                    @endif

                                </form>
                            </tbody>
                        </table>
                      
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- modal  --}}




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
                    url: "{{ route('user.edit') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                       
                        if (response.Code === 200) {
                            window.location.href = window.location.href;
                        }
                    },
                    error: function(response) {
                        alert(response);
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
