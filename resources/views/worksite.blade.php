@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME) 

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
                                                    <img src="{{ asset($SITE->FeaturedImage) }}"
                                                            alt="">
                                                    @endif


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
                                                  
                                                    <li><button onclick="ondelete({{ $SITE->id }})"><i class="fa-solid fa-trash"></i></button></li>
                                                </ul>
                                            </div>
                                        </div>

                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <script>
function ondelete(id){
    window.location.href = window.location.href +'/delete/'+id;
}

                        </script>
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
                            <input type="hidden" name="FeaturedImage" value="" id="FeaturedImage" />
                            <img src="" id="FeaturedImageSRC" width="150px" height="150px" style="display: none" />
                            <button type="button" id="FeaturedImageBTN" data-bs-toggle="modal"
                                data-bs-target="#exampleModal3" type="button">Select Images </button>
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

    {{-- user Gallary  --}}


    <script>
        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            $('#work_site_form').on('submit', (e) => {
                e.preventDefault();
                alert("working");
                let formData1 = new FormData(document.getElementById("work_site_form"));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('create.worksite') }}",
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.Code === 200) {

                            alert("Work Site Created"); // Capture image ID
                            window.location.reload(true);
                        }
                    },
                    error: function(response) {
                        alert("Error ! : " + response.Message);
                    }
                });
            });


        });
    </script>



    {{-- end of Gallary modal  --}}



@endsection
