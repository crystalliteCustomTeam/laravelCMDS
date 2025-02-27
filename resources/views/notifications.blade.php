@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('USERNAME', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="first-top-headerrr">
                        <h5>Communication</h5>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create
                            New</button>
                    </div>
                    <div class="main-natification-table">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Notification</th>
                                    <th scope="col">Work Site</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if ($AllNotification)
                                @php
                                    $index = 1;
                                @endphp
                                @foreach ($AllNotification as $Notification)
                                    @php
                                        $worksiteIds = !empty($Notification->WSID) ? json_decode($Notification->WSID, true) : [];
                                        $areaIds = !empty($Notification->ARIDS) ? json_decode($Notification->ARIDS, true) : [];

                                        // Filter valid worksite and area associations
                                        $validWorksites = $worksiteIds ? collect($WORKSITE)->whereIn('id', $worksiteIds) : collect();
                                        $validAreas = $areaIds ? collect($AREAS)->whereIn('id', $areaIds) : collect();
                                    @endphp

                                    @if ($validWorksites->isNotEmpty() || $validAreas->isNotEmpty())
                                        <tr>
                                            <td>{{ $index++ }}</td>
                                            <td>{{ $Notification->title }}</td>
                                            <td>
                                                @foreach ($validWorksites as $worksite)
                                                    <button
                                                        style="width: fit-content; color: white; padding: 0px 10px">{{ $worksite->Name }}</button>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($validAreas as $area)
                                                    <button
                                                        style="width: fit-content; color: white; padding: 0px 10px">{{ $area->Area_Name }}</button>
                                                @endforeach
                                            </td>
                                            <td>
                                                <button class="delete" onclick="deleteNotification({{ $Notification->id }})">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="main_loadmore-btn">
                            {{-- <button class="load-more">
                                Load More
                            </button> --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this notification?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>


    {{-- modal  --}}
    <script>
        let notificationIdToDelete = null;

        function deleteNotification(ID) {
            notificationIdToDelete = ID;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        }

        // Handle the Delete Confirmation
        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (notificationIdToDelete) {
                window.location.href = window.location.href + '/delete/' + notificationIdToDelete;
            }

            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            deleteModal.hide();
        });


    </script>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create New</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="notificationFORM">
                        <div class="main_flex">
                            <label for="Image">Title</label>
                            <input required type="text" name="title" placeholder="Title">
                        </div>
                        <div class="main_flex">
                            <label for="Image">Message</label>
                            <textarea required name="message" placeholder="Message"></textarea>
                        </div>

                        <div class="main_creat-btn">
                            <button type="submit">Create</button>
                        </div>
                    </form>
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


            $('#notificationFORM').on('submit', (e) => {
                e.preventDefault();

                let formData1 = new FormData(document.getElementById("notificationFORM"));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('notifications.create') }}",
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.Code === 200) {


                            $("#notficationID").val(response.NID);
                            let modal = new bootstrap.Modal(document.getElementById(
                                'exampleModal1'));
                            modal.show();
                        }
                    },
                    error: function(response) {
                        alert("Error ! : " + response.Message);
                    }
                });
            });


            $('#notficationAssign').on('submit', (e) => {
                e.preventDefault();

                let formData1 = new FormData(document.getElementById("notficationAssign"));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('notifications.send') }}",
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log("AJAX Response:", response);

                        // Check the response Code
                        if (response.Code === 200) {
                            // Show success notification
                            toastr.success(response.Message || 'Notification sent successfully!');
                        } else if (response.Code === 206) {
                            // Show warning notification for partial success
                            toastr.warning(response.Message || 'Notification partially sent.');
                            console.log('Partial Success - Failed Users:', response.FailedUsers);
                        } else {
                            toastr.error(response.Message || 'An error occurred.');
                        }

                        // Hide both modals
                        const modal1 = bootstrap.Modal.getInstance(document.getElementById('exampleModal1'));
                        const modal2 = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                        if (modal1) modal1.hide();
                        if (modal2) modal2.hide();

                        // Delay for a moment to show the success message, then refresh the page
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000); // Adjust delay as needed
                    },
                    error: function (response) {
                        console.error("AJAX Error Response:", response);
                        toastr.error(response.responseJSON?.Message || 'An error occurred while sending the notification.');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000); // Adjust delay as needed
                    }
                });
            });

        });
    </script>

    {{-- end modal  --}}


    {{-- user assign modal  --}}

    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog assing-userss">
            <div class="modal-content assing-userss">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel1">Worksite + Area</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="notficationAssign" action="" method="POST">
                        @csrf
                        <input type="hidden" name="notificationID" id="notficationID" value="" />
                        <div class="side-roll">
                            <input type="checkbox" id="select-all">
                            <label for="select-all">Select All</label>
                        </div>
                        <div class="main-checkboxx child-workside" style="overflow: hidden; height: 500px; overflow-y: scroll;">
                            <ul>
                                @if ($WORKSITE)
                                    @foreach ($WORKSITE as $WS)
                                        <li>
                                            <input type="checkbox" class="select-item" id="worksite-{{ $WS->id }}" name="worksiteID[]" value="{{ $WS->id }}">
                                            <label for="worksite-{{ $WS->id }}">{{ $WS->Name }}</label>
                                            <ul>
                                                @if ($AREAS)
                                                    @foreach ($AREAS as $AR)
                                                        @if ($AR->WSID == $WS->id)
                                                            <li>
                                                                <input type="checkbox" class="select-item" id="areasite-{{ $WS->id }}" name="areas[]" value="{{ $AR->id }}">
                                                                <label for="areasite-{{ $WS->id }}">{{ $AR->Area_Name }}</label>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="main_creat-btn mt-3">
                            <button type="submit">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- end of user assign modal  --}}

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Master checkbox for "Select All"
            const selectAllCheckbox = document.getElementById("select-all");

            // All worksite checkboxes
            const worksiteCheckboxes = document.querySelectorAll('input[name="worksiteID[]"]');

            // All child checkboxes (areas)
            const areaCheckboxes = document.querySelectorAll('input[name="areas[]"]');

            // Add event listener to "Select All" checkbox
            selectAllCheckbox.addEventListener("change", function () {
                const isChecked = this.checked;

                // Toggle all worksite and area checkboxes
                worksiteCheckboxes.forEach((checkbox) => (checkbox.checked = isChecked));
                areaCheckboxes.forEach((checkbox) => (checkbox.checked = isChecked));
            });

            // Add event listener to each worksite checkbox
            worksiteCheckboxes.forEach(function (worksiteCheckbox) {
                worksiteCheckbox.addEventListener("change", function () {
                    const worksiteId = this.id.split("-")[1]; // Extract worksite ID
                    const relatedAreas = document.querySelectorAll(
                        `input[id^="areasite-${worksiteId}"]`
                    );

                    // Check/uncheck all related area checkboxes
                    relatedAreas.forEach((checkbox) => (checkbox.checked = this.checked));

                    // Update "Select All" checkbox
                    updateSelectAll();
                });
            });

            // Add event listener to all area checkboxes to update their parent worksite
            areaCheckboxes.forEach(function (areaCheckbox) {
                areaCheckbox.addEventListener("change", function () {
                    const worksiteId = this.id.split("-")[1]; // Extract worksite ID
                    const relatedAreas = document.querySelectorAll(
                        `input[id^="areasite-${worksiteId}"]`
                    );
                    const parentWorksite = document.getElementById(`worksite-${worksiteId}`);

                    // If all areas are checked, check the parent worksite; otherwise, uncheck it
                    const allChecked = Array.from(relatedAreas).every(
                        (checkbox) => checkbox.checked
                    );
                    parentWorksite.checked = allChecked;

                    // Update "Select All" checkbox
                    updateSelectAll();
                });
            });

            // Function to update "Select All" checkbox
            function updateSelectAll() {
                const allWorksitesChecked = Array.from(worksiteCheckboxes).every(
                    (checkbox) => checkbox.checked
                );
                const allAreasChecked = Array.from(areaCheckboxes).every(
                    (checkbox) => checkbox.checked
                );
                selectAllCheckbox.checked = allWorksitesChecked && allAreasChecked;
            }
        });

    </script>


@endsection
