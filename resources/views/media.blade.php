@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

@section('contents')
    <style>
        .progress {
            width: 100%;
            height: 25px;
            background-color: #f5f5f5;
            border-radius: 5px;
            margin-top: 10px;
        }

        .progress-bar {
            height: 100%;
            background-color: #4caf50;
            text-align: center;
            color: white;
            line-height: 25px;
            border-radius: 5px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>Media</h5>
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="files[]" id="files" multiple>
                            <button type="submit">Upload</button>
                        </form>

                        <script>
                            $('#uploadForm').on('submit', function(e) {
                                e.preventDefault();

                                let formData = new FormData(this);

                                $.ajax({
                                    url: "{{ route('media.upload') }}", // Your route
                                    type: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    xhr: function() {
                                        let xhr = new window.XMLHttpRequest();

                                        // Progress event listener
                                        xhr.upload.addEventListener("progress", function(evt) {
                                            if (evt.lengthComputable) {
                                                let percentComplete = Math.round((evt.loaded / evt.total) * 100);

                                                // Update progress bar
                                                $('#progressBar').css('width', percentComplete + '%');
                                                $('#progressBar').text(percentComplete + '%');
                                            }
                                        }, false);

                                        return xhr;
                                    },
                                    beforeSend: function() {
                                        // Show the progress bar before starting upload
                                        $('.progress').show();
                                        $('#progressBar').css('width', '0%');
                                        $('#progressBar').text('0%');
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        alert('Files uploaded successfully!');
                                        $('.progress').hide(); // Hide progress bar after success
                                    },
                                    error: function(response) {
                                        console.log(response);
                                        alert('File upload failed!');
                                        $('.progress').hide(); // Hide progress bar on error
                                    }
                                });
                            });
                        </script>
                    </div>
                    <div class="progress" style="display: none;">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" id="progressBar">0%</div>
                    </div>
                    <div class="mt-5 main-card-container">
                        <ul>
                            @if ($Images)
                                @foreach ($Images as $Image)
                                    <li>
                                        <div class="work-site-box work-site-box1">
                                            <div class="work-site-img">
                                                <img src="{{ asset($Image->image_path) }}" alt="">
                                            </div>
                                            <div class="work-side-content mb-0">
                                                <h6>{{ $Image->image_title }}</h6>
                                            </div>
                                            <ul>
                                                <li><button class="view-image-btn" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal"
                                                        data-image-src="{{ asset($Image->image_path) }}"><i
                                                            class="fa-solid fa-eye"></i></button></li>

                                                <li><button onclick="deleteMedia('{{ $Image->id }}')"><i
                                                            class="fa-solid fa-trash"></i></button></li>
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
    <script>
        function deleteMedia(mediaID) {
            window.location.href = window.location.href+"/delete/"+mediaID;
        }
    </script>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog assing-userss widthsec">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="lightboxImage" src="" alt="Selected Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    {{-- modal  --}}

    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Work Site</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="flex-input">
                            <label for="Image">Image:</label>
                            <input type="file" placeholder="Select Image">
                        </div>
                        <div class="flex-input">
                            <label for="Image">Work Site Name:</label>
                            <input type="text" placeholder="Work Site Name ">
                        </div>
                        <div class="flex-input two-flexx">
                            <div class="datess-input">
                                <label for="Image">Start Date: </label>
                                <input type="date" placeholder="Start Date ">
                            </div>
                            <div class="datess-input">
                                <label for="Image">End Date: </label>
                                <input type="date" placeholder="End Date">
                            </div>

                        </div>
                        <div class="flex-input">
                            <label for="Image">Work Site Name:</label>
                            <input type="text" placeholder="Work Site Name ">
                        </div>
                        <div class="flex-input brief">
                            <label for="Image">Work Site Description </label>
                            <textarea placeholder="Description"></textarea>
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

@endsection
