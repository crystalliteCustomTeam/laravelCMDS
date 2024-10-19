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
                            <input type="hidden" name="FeaturedImage" value="" id="FeaturedImage" />
                            <img src="" id="FeaturedImageSRC" width="150px" height="150px" style="display: none" />
                            <button type="button" id="FeaturedImageBTN" data-bs-toggle="modal"
                                data-bs-target="#exampleModal1" type="button">Select Images </button>
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
