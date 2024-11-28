@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr d-flex justify-content-between align-items-center">
                        <h5>Safety Guidelines</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create New</button>
                    </div>
                    <div class="mt-5 main-safety-card">
                        <ul class="row list-unstyled g-3">
                            @if ($Safety)
                                @foreach ($Safety as $Saf)
                                    <li class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="safety-card p-3 border rounded">
                                            <div class="icons text-center mb-3">
                                                <img src="{{ asset('assets/images/setting-icon.png') }}" alt="Icon" class="img-fluid" style="max-height: 100px;">
                                            </div>
                                            <div class="card-content text-center">
                                                <h5 class="mb-3">{{ $Saf->title }}</h5>
                                            </div>
                                            <ul class="d-flex justify-content-center list-unstyled mb-0">
                                                <li class="mx-2">
                                                    <button onclick="edit({{ $Saf->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" type="button">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                </li>
                                                <li class="mx-2">
                                                    <button onclick="checkDelete({{ $Saf->id }})" class="btn btn-sm btn-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- modal  --}}

    <div class="modal fade" id="deleteSafetyModal" tabindex="-1" aria-labelledby="deleteSafetyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSafetyModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this safety item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmSafetyDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function edit(id) {
            window.location.href = window.location.href + '/edit/' + id
        }

        let safetyIdToDelete = null;
        function checkDelete(id) {
            safetyIdToDelete = id;

            const deleteSafetyModal = new bootstrap.Modal(document.getElementById('deleteSafetyModal'));
            deleteSafetyModal.show();
        }

        document.getElementById('confirmSafetyDeleteBtn').addEventListener('click', function () {
            if (safetyIdToDelete) {
                window.location.href = window.location.href + '/delete/' + safetyIdToDelete;
            }

            const deleteSafetyModal = bootstrap.Modal.getInstance(document.getElementById('deleteSafetyModal'));
            deleteSafetyModal.hide();
        });

    </script>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Safety Guidelines</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="guideline">
                        {{--<div class="flex-input">
                            <label for="Image">Icon:</label>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#iconModal">Select Icon</button>
                            <!-- Hidden Input to Store the Selected Icon Class -->
                            <input type="hidden" id="selectedIconInput" name="selectedIcon" value="fa-solid fa-icons">
                        </div>--}}
                        <div class="flex-input">
                            <label for="Image">Image:</label>
                            <input type="hidden" name="FeaturedImage" value="" id="FeaturedImage" />
                            <img src="" id="FeaturedImageSRC" width="150px" height="150px" style="display: none;" />
                            <button type="button" data-bs-toggle="modal" id="FeaturedImageBTN" data-bs-target="#exampleModal3" type="button">
                                Select Images
                            </button>
                            <small id="imageError" style="color: red; display: none;">Please select an image.</small>
                        </div>
                        <div class="flex-input">
                            <label for="Image">Title: </label>
                            <input type="text" placeholder="Title " name="title" required>
                        </div>
                        <div class="flex-input brief">
                            <label for="Image">Description: </label>
                            <textarea placeholder="Description" name="description" required></textarea>
                        </div>
                        {{-- <div class="assign-user-pop">
                            <button type="button" class="assign-user" data-bs-toggle="modal"
                                data-bs-target="#exampleModal1">Select Check Points </button>
                        </div> --}}
                        <div class="main_creat-btn">
                            <button type="submit">Create</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- end modal  --}}



    {{-- user assign modal  --}}

    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog assing-userss">
            <div class="modal-content assing-userss">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel1">Check Points </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  id="assignForm" action="">
                        @csrf
                        <input type="hidden" value="" name="safety_id" id="safety_id">
                        <div class="main-checkboxx safetly-guide">
                            <ul>
                                @if ($Checkpoint)
                                    @foreach ($Checkpoint as $check)
                                        <li>
                                            <input type="checkbox" name="checkpoint[]" value="{{ $check->id }}">
                                            <label for="">{{ $check->title }}</label>
                                        </li>
                                    @endforeach
                                @endif

                            </ul>
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
            const exampleModal1 = bootstrap.Modal.getInstance(document.getElementById('exampleModal3'));
            exampleModal1.hide();
            const exampleModal = document.getElementById('exampleModal');
            exampleModal.classList.add('show');
            exampleModal.style.display = 'block';
            $('.modal-backdrop').show();
        }
    </script>

    <!-- Icon Modal -->
    <div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iconModalLabel">Select an Icon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Input for Filtering Icons -->
                    <input type="text" id="iconSearch" class="form-control mb-3" placeholder="Search icons...">

                    <!-- Container to Display All Icons -->
                    <div id="iconContainer" class="icon-grid"
                        style="display: flex; flex-wrap: wrap; gap: 15px; height: 400px; overflow-y: scroll;">
                        <!-- Icons will be dynamically populated here -->
                    </div>
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

            $('#guideline').on('submit', (e) => {
                e.preventDefault();

                const featuredImage = $('#FeaturedImage').val(); // Get the value of the hidden input
                const imageError = $('#imageError'); // Get the error message element

                // Check if the image is selected
                if (!featuredImage) {
                    imageError.show(); // Show error message
                    return; // Stop form submission
                } else {
                    imageError.hide(); // Hide error message if the field is valid
                }

                let formData1 = new FormData(document.getElementById("guideline"));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('guideline.create') }}",
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.Code === 200) {
                            toastr.success(response.Message || "Guideline Created");

                            // Show the second modal for assignment
                            const exampleModal1 = new bootstrap.Modal(document.getElementById('exampleModal1'));
                            exampleModal1.show();
                            $('#safety_id').val(response.Safety_ID); // Set the safety_id for assignment
                        }
                    },
                    error: function (response) {
                        toastr.error(response.responseJSON?.Message || "Error occurred while creating the guideline");
                    },
                });
            });

            $('#assignForm').on('submit', function (e) {
                e.preventDefault();

                let formData = $(this).serialize(); // Serialize the form data
                $.ajax({
                    type: 'POST',
                    url: "{{ route('guideline.checkpoint.assign') }}", // Update to your actual route
                    data: formData,
                    success: function (response) {
                        if (response.Code === 200) {
                            // Show success message
                            toastr.success(response.Message || "Checkpoints Assigned Successfully");

                            // Close all modals (exampleModal and exampleModal1)
                            const exampleModal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                            const exampleModal1 = bootstrap.Modal.getInstance(document.getElementById('exampleModal1'));

                            if (exampleModal) exampleModal.hide();
                            if (exampleModal1) exampleModal1.hide();

                            // Remove the backdrop if any modals were closed
                            $('.modal-backdrop').remove();

                            // Refresh the page after a short delay
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000); // Adjust delay as needed (2 seconds here)
                        }
                    },
                    error: function (response) {
                        // Show error message
                        toastr.error(response.responseJSON?.Message || "Error occurred during assignment");

                        setTimeout(function () {
                            window.location.reload();
                        }, 1000); // Adjust delay as needed (2 seconds here)
                    },
                });
            });


        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('exampleModal');
            const closeModalButton = modal.querySelector('[data-bs-dismiss="modal"]');

            // Function to close the modal
            function closeModal() {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
            }

            // Event listener for outside clicks
            function handleOutsideClick(event) {
                const modalContent = modal.querySelector('.modal-content');
                if (!modalContent.contains(event.target)) {
                    closeModal();
                }
            }

            // Add event listeners for modal lifecycle events
            modal.addEventListener('shown.bs.modal', function () {
                // Add outside click listener when the modal is shown
                window.addEventListener('click', handleOutsideClick);
            });

            modal.addEventListener('hidden.bs.modal', function () {
                // Remove outside click listener when the modal is hidden
                window.removeEventListener('click', handleOutsideClick);

                // Remove any leftover modal-backdrop elements
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            });

            // Add listener to the close button
            closeModalButton.addEventListener('click', function () {
                closeModal();
            });
        });
    </script>

@endsection
