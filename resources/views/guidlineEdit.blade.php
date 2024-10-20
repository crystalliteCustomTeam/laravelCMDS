@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME) @section('UFM', $UFM)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>Safety Guidelines</h5>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create New</button>
                    </div>
                    <div class="mt-5 main-safety-card">
                        <ul>
                            

                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- modal  --}}

    <!-- Button trigger modal -->
    <script>
        // function edit(id){
        //     window.location.href = window.location.href +'/edit/'+id
        // }
        function checkDelete(id){
            window.location.href = window.location.href +'/delete/'+id
        }
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
                        <div class="flex-input">
                            <label for="Image">Icon:</label>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#iconModal">Select Icon</button>
                            <!-- Hidden Input to Store the Selected Icon Class -->
                            <input type="hidden" id="selectedIconInput" name="selectedIcon" value="fa-solid fa-icons">
                        </div>
                        <div class="flex-input">
                            <label for="Image">Image:</label>
                            <input type="hidden" name="FeaturedImage" value="" id="FeaturedImage" />
                            <img src="" id="FeaturedImageSRC" width="150px" height="150px" style="display: none" />
                            <button type="button" data-bs-toggle="modal" id="FeaturedImageBTN" data-bs-target="#exampleModal3"
                                type="button">Select Images </button>
                        </div>
                        <div class="flex-input">
                            <label for="Image">Title: </label>
                            <input type="text" placeholder="Title " name="title">
                        </div>
                        <div class="flex-input brief">
                            <label for="Image">Description: </label>
                            <textarea placeholder="Description" name="description"></textarea>
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
                    <form action="">
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

                let formData1 = new FormData(document.getElementById("guideline"));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('guideline.create') }}",
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.Code === 200) {
                            alert("Guidline Created");
                            
                            // window.location.reload();
                        }
                    },
                    error: function(response) {
                        alert("Error ! : " + response.Message);
                    }
                });
            });

           


        });
    </script>




@endsection
