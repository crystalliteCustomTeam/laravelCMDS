@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

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
                                    <a href="{{ route('worksite.singleworksite', ['id' => $SITE->id ]) }}">
                                        <div class="work-site-img">
                                            <img src="{{ asset('assets/images/work-site-img.png') }}" alt="">
                                        </div>
                                        <div class="work-side-content">
                                            <h6>{{ $SITE->Name }}</h6>
                                            <ul>
                                                <li>
                                                    <span><img src="{{ asset('assets/images/black-user.png') }}"
                                                            alt=""></span>
                                                    <span>50</span>
                                                </li>
                                                <li>
                                                    <span><img src="{{ asset('assets/images/black-alarm.png') }}"
                                                            alt=""></span>
                                                    <span>10</span>
                                                </li>
                                                <li>
                                                    <span><img src="{{ asset('assets/images/near-mises.png') }}"
                                                            alt=""></span>
                                                    <span>50</span>
                                                </li>
                                                <li>
                                                    <span><img src="{{ asset('assets/images/accidents.png') }}"
                                                            alt=""></span>
                                                    <span>10</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </a>
                                    <div class="main_editss-options">
                                        <ul>
                                            <li><button data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                    type="button"><i class="fa-solid fa-pen-to-square"></i></button>
                                            </li>
                                            <li><button><i class="fa-solid fa-trash"></i></button></li>
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

    {{-- modal  --}}

    <!-- Button trigger modal -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal1"
                                type="button">Select Images </button>
                            <input type="hidden" name="fImage" value="" id="fImage" />
                            <image src="" width="150px" hight="150px" id="fimagesrc" />
                        </div>
                        <div class="flex-input">
                            <label for="Image">Work Site Name:</label>
                            <input type="text" name="site_name" placeholder="Work Site Name ">
                        </div>
                        <div class="flex-input two-flexx">
                            <div class="datess-input">
                                <label for="Image">Start Date: </label>
                                <input type="date" name="start_date" placeholder="Start Date ">
                            </div>
                            <div class="datess-input">
                                <label for="Image">End Date: </label>
                                <input type="date" name="end_date" placeholder="End Date">
                            </div>

                        </div>
                       
                        <div class="flex-input brief">
                            <label for="Image">Work Site Description </label>
                            <textarea name="description" placeholder="Description"></textarea>
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



    {{-- user Gallary  --}}

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
                                    aria-controls="home-tab-pane" aria-selected="true">Upload</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile-tab-pane" type="button" role="tab"
                                    aria-controls="profile-tab-pane" aria-selected="false">Media</button>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="0">
                                <div class="main-upload">
                                    <form id="imageUploadForm" enctype="multipart/form-data">
                                        @csrf
                                        <h5>Upload an image / Video </h5>

                                        <input type="file" name="image" id="fileInput" accept="image/*">
                                        <button type="submit">Upload</button>
                                    </form>
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

                                                // Show the uploading status
                                                $('#uploadingStatus').show();
                                                $('#progressBarContainer').show();

                                                $.ajax({
                                                    type: 'POST',
                                                    url: "{{ route('image.worksite.image') }}",
                                                    data: formData,
                                                    contentType: false,
                                                    processData: false,
                                                    xhr: function() {
                                                        var xhr = new XMLHttpRequest();
                                                        xhr.upload.addEventListener('progress', function(e) {
                                                            if (e.lengthComputable) {
                                                                var percentComplete = (e.loaded / e.total) * 100;
                                                                $('#progressBar').css('width', percentComplete + '%');
                                                            }
                                                        }, false);
                                                        return xhr;
                                                    },
                                                    success: function(response) {
                                                        if (response.success) {
                                                            $('#uploadingStatus').hide();
                                                            $('#progressBarContainer').hide();
                                                            $('#progressBar').css('width', '0%'); // Reset progress bar
                                                            $('#uploadSuccess').show();
                                                            $('#uploadedImage').attr('src', '/images/' + response.image).show();
                                                            $("#fImage").attr('value', response.name);
                                                            $("#fimagesrc").attr('src', '/images/' + response.image);
                                                            $("#exampleModal1").css('display', 'none');
                                                            let backdrops = document.querySelectorAll('.modal-backdrop');
                                                            backdrops.forEach(function(backdrop) {
                                                                backdrop.style.display = "none";
                                                            });
                                                            console.log('Uploaded Image ID:', response.id); // Capture image ID
                                                        }
                                                    },
                                                    error: function(response) {
                                                        console.log(response);
                                                        alert('Image upload failed.');
                                                        $('#uploadingStatus').hide();
                                                        $('#progressBarContainer').hide();
                                                    }
                                                });
                                            });

                                            $('#work_site_form').on('submit',(e)=>{
                                                e.preventDefault();
                                                alert("working");
                                                let formData1 = new FormData(document.getElementById("work_site_form"));
                                                $.ajax({
                                                    type: 'POST',
                                                    url: "{{ route('create.worksite') }}",
                                                    data: formData1,
                                                    contentType: false,
                                                    processData: false,
                                                    xhr: function() {
                                                        var xhr = new XMLHttpRequest();
                                                        xhr.upload.addEventListener('progress', function(e) {
                                                            if (e.lengthComputable) {
                                                                var percentComplete = (e.loaded / e.total) * 100;
                                                                $('#progressBar').css('width', percentComplete + '%');
                                                            }
                                                        }, false);
                                                        return xhr;
                                                    },
                                                    success: function(response) {
                                                        if (response.success) {
                                                          
                                                            console.log("Work Site Created"); // Capture image ID
                                                            window.location.reload(true);
                                                        }
                                                    },
                                                    error: function(response) {
                                                       alert("Error ! : "+response.Message);
                                                    }
                                                });
                                            });


                                        });
                                    </script>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
                                aria-labelledby="profile-tab" tabindex="0">
                                <div class="mediaaa">
                                    <form action="">
                                        <div class="media-selection-page">
                                            <ul id="work-site-list">
                                                @for ($i = 0; $i < 10; $i++)
                                                    <li class="work-site-item" onclick="toggleSelect(this)">
                                                        <div class="work-site-box work-site-box{{ $i + 1 }}">
                                                            <div class="work-site-img">
                                                                <img src="{{ asset('assets/images/work-site-img.png') }}"
                                                                    alt="">
                                                            </div>
                                                            <div class="work-side-content mb-0">
                                                                <h6>IMG_238{{ $i + 1 }}.jpg</h6>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <div class="main_creat-btn">
                                            <button type="submit">Submit</button>
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



@endsection
