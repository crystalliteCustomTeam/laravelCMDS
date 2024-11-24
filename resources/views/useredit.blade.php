@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="user-edit-form p-4 shadow-sm rounded">
                        <h5 class="text-center mb-4">Edit Users</h5>
                        <form id="imageUploadForm" enctype="multipart/form-data">
                            @if (count($USER_DATA) > 0)
                                @foreach ($USER_DATA as $user)
                                    <!-- User Details Section -->
                                    <input type="hidden" name="userID" value="{{ $user->UID }}" />

                                    <div class="mb-3">
                                        <label for="name_{{ $user->UID }}" class="form-label">Name</label>
                                        <input
                                            type="text"
                                            name="name"
                                            id="name_{{ $user->UID }}"
                                            class="editable-field form-control"
                                            data-user-id="{{ $user->UID }}"
                                            data-field="name"
                                            value="{{ $user->name }}"
                                            required
                                        />
                                    </div>

                                    <div class="mb-3">
                                        <label for="email_{{ $user->UID }}" class="form-label">Email</label>
                                        <input
                                            type="email"
                                            name="email"
                                            id="email_{{ $user->UID }}"
                                            class="editable-field form-control"
                                            data-user-id="{{ $user->UID }}"
                                            data-field="email"
                                            value="{{ $user->email }}"
                                            required
                                        />
                                    </div>

                                    <div class="mb-3">
                                        <label for="role_{{ $user->UID }}" class="form-label">Role</label>
                                        <select
                                            name="role"
                                            id="role_{{ $user->UID }}"
                                            class="editable-field form-select"
                                            data-user-id="{{ $user->UID }}"
                                            data-field="role"
                                            required
                                        >
                                            <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Safety Manager</option>
                                            <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Worker</option>
                                        </select>
                                    </div>

                                    <!-- Image Section -->
                                    <div class="text-center">
                                        <label class="form-label">Image</label>
                                        <div class="image-preview mb-3 mx-auto">
                                            <img
                                                id="FeaturedImageSRC_{{ $user->UID }}"
                                                src="{{ asset($user->featuredImage) }}"
                                                alt="Featured Image"
                                                class="img-thumbnail rounded-circle"
                                                style="width: 150px; height: 150px;"
                                            />
                                        </div>
                                        <input
                                            type="hidden"
                                            name="FeaturedImage"
                                            id="FeaturedImage_{{ $user->UID }}"
                                            value="{{ $user->featuredImage }}"
                                        />
                                        <button
                                            type="button"
                                            id="FeaturedImageBTN_{{ $user->UID }}"
                                            class="btn btn-primary w-50"
                                            data-bs-toggle="modal"
                                            data-bs-target="#exampleModal1"
                                            onclick="setCurrentImageField({{ $user->UID }})"
                                        >
                                            Change Image
                                        </button>
                                    </div>


                                @endforeach
                                <!-- Save Button -->
                                <div class="text-center mt-4">
                                    <button type="submit" id="saveButton" class="btn btn-success px-5">Save</button>
                                </div>
                            @else
                                <p class="text-center">No User Found</p>
                            @endif
                        </form>
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
                                        <button type="submit" style="background: #14173A;
                                    border-radius: 10px 10px 0 0;
                                    color: white;
                                    padding: 7px 20px 7px 20px;
                                    border: 0;
                                    margin: 0 15px 0 0;">Upload Files</button>
                                    </form>
                                    <script>

                                        document.getElementById('imageuploadtab').addEventListener('submit', function (event) {
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
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                }
                                            })
                                                .then((response) => response.json())
                                                .then((data) => {
                                                    let workSiteList = document.getElementById('work-site-list');

                                                    // Loop through each uploaded image and append it to the media list
                                                    data.uploadedImages.forEach((image) => {
                                                        let newListItem = document.createElement('li');
                                                        newListItem.className = 'work-site-item';
                                                        newListItem.setAttribute('onclick', `selectImage('${image.image_path}')`);

                                                        newListItem.innerHTML = `
                    <div class="work-site-box">
                        <div class="work-site-img">
                            <img src="${image.image_path}" alt="" class="img-thumbnail" />
                        </div>
                        <div class="work-side-content mb-0">
                            <h6>${image.image_title}</h6>
                        </div>
                    </div>
                `;

                                                        workSiteList.appendChild(newListItem);
                                                    });

                                                    // Automatically switch to the Media tab
                                                    document.getElementById('profile-tab').click();
                                                })
                                                .catch((error) => {
                                                    console.error('Error:', error);
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

    <style>
        /* Ensure the modal backdrop is properly hidden */
        .modal-backdrop {
            z-index: 1040;
        }

        /* Adjust the z-index of the modal content */
        .modal-content {
            z-index: 1050;
        }

        /* Ensure the main content is displayed correctly */
        .user-edit-form {
            position: relative;
            z-index: 1; /* Keeps it below the modal */
        }
    </style>

    <script>
        /*function selectImage(imagePath) {
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
        }*/

        let currentUserID = null;

        function setCurrentImageField(userID) {
            currentUserID = userID; // Set the current user ID when the Change Image button is clicked
        }

        function selectImage(imagePath) {
            if (currentUserID !== null) {
                // Find the corresponding hidden input and image preview for the current user
                const hiddenInput = document.querySelector(`#FeaturedImage_${currentUserID}`);
                const imagePreview = document.querySelector(`#FeaturedImageSRC_${currentUserID}`);

                if (hiddenInput && imagePreview) {
                    hiddenInput.value = imagePath; // Update the hidden input's value with the selected image path
                    imagePreview.src = `${window.location.origin}/${imagePath}`; // Update the image preview's source
                }

                // Close the modal and clean up
                const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal1'));
                modal.hide();

                // Remove lingering modal backdrops if any
                document.querySelectorAll('.modal-backdrop').forEach((backdrop) => backdrop.remove());
            }
        }

    </script>

@endsection
