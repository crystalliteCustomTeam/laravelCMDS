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
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Image
                                </th>
                                <th>
                                    Video
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Description
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <form  method="POST" action="{{ route('checkpoint.edit.post')  }}">
                                @csrf
                                <tr>
                                    <td>
                                        <input type="hidden" value="{{ $checkpoint->id }}" name="checkpointID">
                                        <img src="{{ asset($checkpoint->Images) }}" id="FeaturedImageSRC" alt=""
                                            style="border-radius: 10%" width="100px" height="100px">
                                        <input type="hidden" name="FeaturedImage" value="{{ asset($checkpoint->Images) }}" id="FeaturedImage" /></br>
                                        <button type="button" id="FeaturedImageBTN" class="btn btn-dark mt-2" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal1" type="button">Change Image </button>


                                    </td>
                                    <td>
                                        @if ($checkpoint->Videos != '')
                                            <input type="text" class="form-control" name="video"
                                                value="No Video Added">
                                        @else
                                            <video src="{{ $checkpoint->Videos }}" name="video" width="300px" height="300px"></video>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="title"
                                            value="{{ $checkpoint->title }}">
                                    </td>
                                    <td>
                                        <textarea name="description" class="form-control">{{ $checkpoint->Description }}</textarea>
                                        <input type="submit" class="btn btn-success mt-2" name="submit" value="Edit">
                                    </td>



                                </tr>
                            </form>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>

    {{-- modal  --}}

    <!-- Button trigger modal -->



    {{-- end modal  --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });




            $('#checkpointsform').on('submit', (e) => {
                e.preventDefault();

                let formData1 = new FormData(document.getElementById("checkpointsform"));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('checkpoint.edit.post') }}",
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.Code === 200) {
                            alert("Checkpoint Created");
                            window.location.reload();
                        }
                    },
                    error: function(response) {
                        alert("Error ! : " + response.Message);
                    }
                });
            });


        });
    </script>


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
