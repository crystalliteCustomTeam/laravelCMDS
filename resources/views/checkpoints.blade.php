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
                            <button type="button" id="FeaturedImageBTN" data-bs-toggle="modal"
                                data-bs-target="#exampleModal1" type="button">Select Images </button>
                        </div>
                        <div class="flex-input">
                            <label for="Image">Video:</label>
                            <input type="text" name="videoURL" placeholder="Video Url" />
                        </div>
                        <div class="flex-input">
                            <label for="Image">Title:</label>
                            <input type="text" name="title" placeholder="Title">
                        </div>

                        <div class="flex-input brief">
                            <label for="Image">Description: </label>
                            <textarea name="description" placeholder="Description"></textarea>
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
                    url: "{{ route('checkpoint.create') }}",
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
                                    <button type="button" id="uploadButton">Upload</button>
                                    <input type="file" id="fileInput" accept="image/*" style="display: none;"
                                        multiple>
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
