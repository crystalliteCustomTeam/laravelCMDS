@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)

<style>
    #loader img {
        width: 50px; /* Adjust size as needed */
        height: 50px;
    }

</style>

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>Work Sites</h5>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create Work
                            Site</button>
                    </div>
                    <div class="mt-5 main-card-container site-work">
                        <ul>
                            @if ($SITES)
                                @foreach ($SITES as $SITE)
                                    <li>

                                        <div class="work-site-box">
                                            <a href="{{ route('worksite.singleworksite', ['id' => $SITE->id]) }}">
                                                <div class="work-site-img">
                                                    @if ($SITE->FeaturedImage == '')
                                                        <img src="{{ asset('assets/images/work-site-img.png') }}"
                                                            alt="">
                                                    @else
                                                        <img src="{{ asset($SITE->FeaturedImage) }}" alt="">
                                                    @endif


                                                </div>
                                                <div class="work-side-content">
                                                    <h6>{{ $SITE->Name }}</h6>
                                                    <ul>
                                                        <li>
                                                            <span><img src="{{ asset('assets/images/black-user.png') }}"
                                                                    alt=""></span>
                                                            <span>{{ $SITE->area_users_count }}</span>
                                                        </li>
                                                        <li>
                                                            <span><img src="{{ asset('assets/images/black-alarm.png') }}"
                                                                    alt=""></span>
                                                            <span> {{ count_notifications($SITE->id) }}</span>
                                                        </li>
                                                        <li>
                                                            <span><img src="{{ asset('assets/images/near-mises.png') }}"
                                                                    alt=""></span>
                                                            <span>{{ $SITE->total_area_accidents }}</span>
                                                        </li>
                                                        <li>
                                                            <span><img src="{{ asset('assets/images/accidents.png') }}"
                                                                    alt=""></span>
                                                            <span>{{ countalert($SITE->id) }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                            <div class="main_editss-options">
                                                <ul>

                                                    <li><button onclick="ondelete({{ $SITE->id }})"><i
                                                                class="fa-solid fa-trash"></i></button></li>
                                                </ul>
                                            </div>
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


    <div class="modal fade" id="deleteSiteModal" tabindex="-1" aria-labelledby="deleteSiteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSiteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this site?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmSiteDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        let siteIdToDelete = null;

        function ondelete(id) {
            siteIdToDelete = id;

            const deleteSiteModal = new bootstrap.Modal(document.getElementById('deleteSiteModal'));
            deleteSiteModal.show();
        }

        document.getElementById('confirmSiteDeleteBtn').addEventListener('click', function () {
            if (siteIdToDelete) {
                window.location.href = window.location.href + '/delete/' + siteIdToDelete;
            }

            const deleteSiteModal = bootstrap.Modal.getInstance(document.getElementById('deleteSiteModal'));
            deleteSiteModal.hide();
        });

    </script>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Work Site</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="work_site_form">
                        <div class="flex-input">
                            <label for="Image">Image:</label>
                            <input type="hidden" name="FeaturedImage" value="" id="FeaturedImage" />
                            <img src="" id="FeaturedImageSRC" width="150px" height="150px" style="display: none" />
                            <button type="button" id="FeaturedImageBTN" data-bs-toggle="modal" data-bs-target="#exampleModal3" type="button">Select Images</button>
                            <div class="validation-message" id="FeaturedImageValidation" style="color: red; display: none;">Please select an image.</div>
                        </div>

                        <div class="flex-input">
                            <label for="site_name">Work Site Name:</label>
                            <input type="text" name="site_name" placeholder="Work Site Name" id="site_name" required pattern=".{3,}" title="Work Site Name must be at least 3 characters long." />
                        </div>

                        <div class="flex-input two-flexx">
                            <div class="datess-input">
                                <label for="start_date">Start Date: </label>
                                <input type="date" name="start_date" id="start_date" required />
                            </div>

                            @php
                                $minDate = now()->format('Y-m-d'); // Current date only
                            @endphp

                            <div class="datess-input">
                                <label for="end_date">End Date: </label>
                                <input type="date" name="end_date" id="end_date" required min="{{ $minDate }}" />
                                <div id="endDateValidation" style="color: red; display: none;">End date must be greater than or equal to start date.</div>
                            </div>
                        </div>

                        <div class="flex-input brief">
                            <label for="description">Work Site Description</label>
                            <textarea name="description" id="description" placeholder="Description" required minlength="10" title="Description must be at least 10 characters long."></textarea>
                        </div>

                        <div class="main_creat-btn">
                            <button name="submit" type="submit">Create</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    {{-- end modal  --}}


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

    <div id="loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;">
        <img src="{{ asset('assets/images/loader.gif') }}" alt="Loading..." style="width: 50px; height: 50px;">
    </div>
    {{-- end of Gallary modal  --}}
    {{--<script>
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
    </script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        function selectImage(imagePath) {
            // Set the selected image value
            $("#FeaturedImage").val(imagePath);
            let ImageURL = window.location.origin + "/" + imagePath;
            $("#FeaturedImageSRC").attr('src', ImageURL).show();

            // Close exampleModal3 (Gallery Modal)
            const exampleModal3 = bootstrap.Modal.getInstance(document.getElementById('exampleModal3'));
            if (exampleModal3) {
                exampleModal3.hide();
            }

            // Remove any lingering backdrop after hiding exampleModal3
            $('.modal-backdrop').remove();

            // Open exampleModal (Work Site Modal) with a static backdrop to prevent outside clicks from closing it
            const exampleModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
                backdrop: 'static', // Prevent closing by clicking outside
                keyboard: false // Prevent closing by pressing ESC
            });

            exampleModal.show();

            // Ensure the backdrop is removed after exampleModal is closed
            $('#exampleModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove(); // Manually remove any lingering backdrop
            });
        }

        // Prevent modal close when clicking on the backdrop (for exampleModal)
        $(document).on('click', '.modal-backdrop', function (e) {
            // If the target clicked is not inside a modal content, prevent the modal close
            if ($(e.target).closest('.modal-content').length === 0) {
                e.stopPropagation(); // Prevent backdrop click from closing the modal
            }
        });

        // When the user clicks outside the modal, we need to manually hide the backdrop
        $(document).on('click', function (event) {
            const modalElement = $('.modal.show');
            if (!modalElement.is(event.target) && modalElement.has(event.target).length === 0) {
                // Close any modal and remove the backdrop when clicked outside
                $('.modal-backdrop').remove();
            }
        });

    </script>

    {{-- user Gallary  --}}


    <script>
        $(document).ready(function(e) {
            $('#loader').hide();

            $('#start_date').on('change', function () {
                const startDate = $(this).val();
                $('#end_date').attr('min', startDate); // Set min attribute of End Date
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            $('#work_site_form').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission


                let featuredImage = $('#FeaturedImage').val();
                let featuredImageValidation = $('#FeaturedImageValidation');

                if (!featuredImage) {
                    featuredImageValidation.show();
                    return false;
                } else {
                    featuredImageValidation.hide();
                }

                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (endDate < startDate) {
                    $('#endDateValidation').show();
                    return false;
                } else {
                    $('#endDateValidation').hide();
                }

                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('create.worksite') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.Code === 200) {
                            $('#loader').show();
                            toastr.success(response.Message || "Worksite Created");
                            setTimeout(() => {
                                window.location.reload(true);
                            }, 1000);
                        }
                    },
                    error: function () {
                        toastr.error(response.responseJSON?.Message || "Error occurred while creating the guideline");
                        setTimeout(() => {
                            window.location.reload(true);
                        }, 1000);
                    }
                });
            });
        });
    </script>



    {{-- end of Gallary modal  --}}



@endsection
