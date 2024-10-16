@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>Users</h5>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Save</button>
                    </div>
                    <div class="main-natification-table">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form id="imageUploadForm" enctype="multipart/form-data">
                                    @if (Count($USER_DATA) > 0)
                                        @php
                                            $user_count = 0;
                                        @endphp
                                        @foreach ($USER_DATA as $user)
                                            <tr>
                                                <td><input required type="text" name="name"
                                                        value="{{ $user->name }}" />
                                                </td>
                                                <td> <input required type="email" name="email"
                                                        value="{{ $user->email }}" /></td>
                                                <td>
                                                    @if ($user->role == 1)
                                                        Safety Manager
                                                    @else
                                                        Worker
                                                    @endif
                                                    </br>
                                                    <select name="role" required>

                                                        <option value="1">Safety Manager</option>
                                                        <option value="2">Worker</option>
                                                    </select>

                                                </td>
                                                <td><img src="{{ asset('images/' . $user->featuredImage) }}" width="200"
                                                        height="200" /></td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6"> NO User Found</td>
                                        </tr>

                                    @endif

                                </form>
                            </tbody>
                        </table>
                        <div class="main_loadmore-btn">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- modal  --}}




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
                    url: "{{ route('image.upload') }}",
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

                            window.location.reload;
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
        });
    </script>


    {{-- end of Gallary modal  --}}


@endsection
