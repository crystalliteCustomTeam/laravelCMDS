@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

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
                        <li>
                            <div class="safety-card">
                                <div class="icons">
                                    <img src="{{asset('assets/images/setting-icon.png')}}" alt="">
                                </div>
                                <div class="card-content">
                                    <h5>Rooftop</h5>
                                </div>
                                <ul>
                                    <li><button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button"><i class="fa-solid fa-pen-to-square"></i></button></li>
                                    <li><button><i class="fa-solid fa-trash"></i></button></li>
                                </ul>
                            </div>

                            
                        </li>
                        <li>
                            <div class="safety-card">
                                <div class="icons">
                                    <img src="{{asset('assets/images/setting-icon.png')}}" alt="">
                                </div>
                                <div class="card-content">
                                    <h5>Rooftop</h5>
                                </div>
                                <ul>
                                    <li><button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button"><i class="fa-solid fa-pen-to-square"></i></button></li>
                                    <li><button><i class="fa-solid fa-trash"></i></button></li>
                                </ul>
                            </div>

                            
                        </li>
                        <li>
                            <div class="safety-card">
                                <div class="icons">
                                    <img src="{{asset('assets/images/setting-icon.png')}}" alt="">
                                </div>
                                <div class="card-content">
                                    <h5>Rooftop</h5>
                                </div>
                                <ul>
                                    <li><button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button"><i class="fa-solid fa-pen-to-square"></i></button></li>
                                    <li><button><i class="fa-solid fa-trash"></i></button></li>
                                </ul>
                            </div>

                            
                        </li>
                        <li>
                            <div class="safety-card">
                                <div class="icons">
                                    <img src="{{asset('assets/images/setting-icon.png')}}" alt="">
                                </div>
                                <div class="card-content">
                                    <h5>Rooftop</h5>
                                </div>
                                <ul>
                                    <li><button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button"><i class="fa-solid fa-pen-to-square"></i></button></li>
                                    <li><button><i class="fa-solid fa-trash"></i></button></li>
                                </ul>
                            </div>

                            
                        </li>
                    </ul>
                  
                </div>
            </div>
        </div>
    </div>
</section>

{{-- modal  --}}

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Safety Guidelines</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="flex-input">
                        <label for="Image">Icon:</label>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#iconModal">Select Icon</button>
                         <!-- Hidden Input to Store the Selected Icon Class -->
                         <input type="hidden" id="selectedIconInput" name="selectedIcon" value="fa-solid fa-icons">
                    </div>
                    <div class="flex-input">
                        <label for="Image">Image:</label>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal2" type="button">Select Images </button>
                    </div>
                    <div class="flex-input">
                        <label for="Image">Title: </label>
                        <input type="text" placeholder="Title ">
                    </div>
                    <div class="flex-input brief">
                        <label for="Image">Description: </label>
                       <textarea placeholder="Description"></textarea>
                    </div>
                    <div class="assign-user-pop">
                        <button  type="button" class="assign-user" data-bs-toggle="modal" data-bs-target="#exampleModal1">Select Check Points </button>
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
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>

                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>
                    <li>
                        <input type="checkbox">
                        <label for="">Check Point 01</label>
                    </li>

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


{{-- user Gallary  --}}

<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
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
                      <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Upload</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Media</button>
                    </li>
                    
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="main-upload">
                            <h5>Upload an image / Video </h5>
                            <button type="button" id="uploadButton">Upload</button>
                            <input type="file" id="fileInput" accept="image/*" style="display: none;" multiple>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <div class="mediaaa">
                            <form action="">
                                <div class="media-selection-page">
                                    <ul id="work-site-list">
                                        @for ($i = 0; $i < 10; $i++)
                                            <li class="work-site-item" onclick="toggleSelect(this)">
                                                <div class="work-site-box work-site-box{{ $i + 1 }}">
                                                    <div class="work-site-img">
                                                        <img src="{{ asset('assets/images/work-site-img.png') }}" alt="">
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
                <div id="iconContainer" class="icon-grid" style="display: flex; flex-wrap: wrap; gap: 15px; height: 400px; overflow-y: scroll;">
                    <!-- Icons will be dynamically populated here -->
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
