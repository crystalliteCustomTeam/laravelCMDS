@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>Check Points</h5>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create New</button>
                    </div>
                    <div class="mt-5 area-boxes">
                        <ul>
                            @if ($checkpoint)
                                @foreach ($checkpoint as $ckp)
                                    <li class="mt-2 " style="margin-right: 15px">
                                        <div class="area">{{ $ckp->title }}</div>
                                        <ul>
                                            <li><button onclick="edit({{ $ckp->id }})" type="button"><i
                                                        class="fa-solid fa-pen-to-square"></i></button></li>
                                            <li><button onclick="checkDelete({{ $ckp->id }})" ><i class="fa-solid fa-trash"></i></button></li>
                                        </ul>
                                    </li>

                                @endforeach
                            @endif


                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        function edit(id){
            window.location.href = window.location.href +'/edit/'+id
        }
        function checkDelete(id){
            window.location.href = window.location.href +'/delete/'+id
        }
    </script>
    {{-- modal  --}}

    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Check Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="checkpointsform">
                        <div class="flex-input">
                            <label for="Image">Image:</label>
                            <input type="hidden" name="FeaturedImage" value="" id="FeaturedImage" />
                            <img src="" id="FeaturedImageSRC" width="150px" height="150px" style="display: none" />
                            <button type="button" id="FeaturedImageBTN" data-bs-toggle="modal" data-bs-target="#exampleModal1" type="button">Select Images</button>
                            <div class="validation-message" id="FeaturedImageValidation" style="color: red; display: none;">Please select an image.</div>
                        </div>
                        <div class="flex-input">
                            <label for="videoURL">Video:</label>
                            <input type="url" name="videoURL" id="videoURL" placeholder="Video Url" pattern="https?://.+" />
                            <div class="validation-message" id="VideoURLValidation" style="color: red; display: none;">Please enter a valid URL (e.g., https://example.com).</div>
                        </div>
                        <div class="flex-input">
                            <label for="title">Title:</label>
                            <input type="text" name="title" id="title" placeholder="Title" required minlength="3" maxlength="100" />
                            <div class="validation-message" id="TitleValidation" style="color: red; display: none;">Title must be between 3 and 100 characters.</div>
                        </div>
                        <div class="flex-input brief">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" placeholder="Description" required minlength="10" maxlength="500"></textarea>
                            <div class="validation-message" id="DescriptionValidation" style="color: red; display: none;">Description must be between 10 and 500 characters.</div>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            // List of modal IDs to remove the backdrop
            const modals = ['exampleModal', 'exampleModal1']; // Add your modal IDs here

            modals.forEach(modalId => {
                const modalElement = document.getElementById(modalId);

                if (modalElement) {
                    // Remove backdrop and keyboard on modal show
                    modalElement.addEventListener('show.bs.modal', () => {
                        // Dynamically set attributes to remove overlay effect
                        modalElement.setAttribute('data-bs-backdrop', 'false');
                        modalElement.setAttribute('data-bs-keyboard', 'false');

                        // Manually remove any existing backdrop element
                        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                    });
                }
            });
        });


        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            // Submit the form for checkpoint creation
            $('#checkpointsform').on('submit', function(e) {
                e.preventDefault();

                let featuredImage = $('#FeaturedImage').val();
                if (!featuredImage) {
                    $('#FeaturedImageValidation').show(); // Show validation message
                    return false; // Stop form submission
                } else {
                    $('#FeaturedImageValidation').hide();
                    let formData1 = new FormData(document.getElementById("checkpointsform"));
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('checkpoint.create') }}",
                        data: formData1,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.Code === 200) {
                                // Show success toast
                                toastr.success("Checkpoint Created Successfully!");

                                // Optionally reset the form
                                $('#checkpointsform')[0].reset();
                                $('#FeaturedImageSRC').hide(); // Hide the preview image

                                // Close the modal
                                $('#exampleModal').modal('hide');

                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(response) {
                            // Show error toast
                            toastr.error(response.responseJSON?.Message || "Error occurred while creating checkpoint.");

                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    });
                }

            });
        });
    </script>


    {{-- user Gallary  --}}

    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog assing-userss">
            <div class="modal-content assing-userss">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel1">Gallery </h1>
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
            const exampleModal1 = bootstrap.Modal.getInstance(document.getElementById('exampleModal1'));
            exampleModal1.hide();
            const exampleModal = document.getElementById('exampleModal');
            exampleModal.classList.add('show');
            exampleModal.style.display = 'block';
            $('.modal-backdrop').show();
        }
    </script>



@endsection
