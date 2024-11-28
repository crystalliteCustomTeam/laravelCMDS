<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://www.gstatic.com/firebasejs/11.0.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/11.0.1/firebase-firestore-compat.js"></script>
    <script src="{{ asset('assets/js/firebase.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="dashboard-layout">
    <div class="container-fluid">
        <div class="dashboard-layout">
            <div class="first-side-nav">
                <nav>
                    <ul>
                        <li><a href="javascript:;" class="logo">
                                <img src="{{ asset('assets/images/vnexia-white-logo-nav.png') }}" alt="">
                            </a></li>
                        <li><a href="{{ route('dashboard') }}" class="navigat-items">
                                <img src="{{ asset('assets/images/dashboard-nav.png') }}" alt="">
                                <span class="nav-item">Dashboard</span>
                            </a></li>
                        <li><a href="{{ route('worksite') }}" class="navigat-items">
                                <img src="{{ asset('assets/images/construction-site-nav.png') }}" alt="">
                                <span class="nav-item">Work Sites</span>
                            </a></li>
                        <li><a href="{{ route('notifications') }}" class="navigat-items">
                                <img src="{{ asset('assets/images/alarm-nav.png') }}" alt="">
                                <span class="nav-item">Communication</span>
                            </a></li>
                        {{--<li><a href="{{ route('guideline') }}" class="navigat-items">
                                <img src="{{ asset('assets/images/checking-nav.png') }}" alt="">
                                <span class="nav-item">Safety Guidelines</span>
                            </a>
                            <ul id="navigation">
                                <li><a href="{{ route('checkpoint') }}" class="navigat-items">
                                        <img src="{{ asset('assets/images/checkinglist-nav.png') }}" alt="">
                                        <span class="nav-item">Check Points</span>
                                    </a></li>
                            </ul>
                        </li>--}}

                        <li>
                            <!-- Parent Link -->
                            <a href="{{ route('guideline') }}"
                               class="navigat-items {{ request()->routeIs('guideline') || request()->routeIs('checkpoint') ? 'active' : '' }}">
                                <img src="{{ asset('assets/images/checking-nav.png') }}" alt="">
                                <span class="nav-item">Safety Guidelines</span>
                            </a>

                            <!-- Child Submenu -->
                            <ul id="navigation" class="{{ request()->routeIs('checkpoint') || request()->routeIs('guideline') ? 'active' : '' }}">
                                <li>
                                    <a href="{{ route('checkpoint') }}"
                                       class="navigat-items {{ request()->routeIs('checkpoint') ? 'active' : '' }}">
                                        <img src="{{ asset('assets/images/checkinglist-nav.png') }}" alt="">
                                        <span class="nav-item">Check Points</span>
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li><a href="{{ route('users') }}" class="navigat-items">
                                <img src="{{ asset('assets/images/user-nav.png') }}" alt="">
                                <span class="nav-item">Users</span>
                            </a></li>
                        <li><a href="{{ route('media') }}" class="navigat-items">
                                <img src="{{ asset('assets/images/media-gallery-nav.png') }}" alt="">
                                <span class="nav-item">Media</span>
                            </a></li>
                        <li><a href="/logout" class="navigat-items" class="logout">
                                <img src="{{ asset('assets/images/logout-nav.png') }}" alt="">
                                <span class="nav-item">Log out</span>
                            </a></li>
                    </ul>
                </nav>
            </div>
            <div class="body-sec">


                <div class="secon-side-nav">
                    <div class="welcome-start">
                        <h2 style="text-transform: uppercase;">WELCOME @yield('UserName')</h2>
                    </div>
                    <div class="nav-userss">
                        <ul>
                            <li>
                                <select>
                                    <option value="English" selected>English</option>
                                </select>
                            </li>
                            <li>
                                <div class="main_users_card-header">
                                    @php
                                    $userMeta = \App\Models\UserMeta::query()->where('userId', \Illuminate\Support\Facades\Auth::id())->first();
                                    @endphp
                                    <div class="user-name">
                                        <h4>@yield('USERNAME')</h4>
                                        <p>
                                            @if (empty($userMeta->role) || $userMeta->role == 2)
                                                Safety Manager
                                            @else
                                                Worker
                                            @endif
                                        </p>
                                    </div>
                                    <div class="user-img">
                                        @if ($UFM != null && $UFM->featuredImage != null)
                                            @if ($UFM->featuredImage == '')
                                                <img src="{{ asset('assets/images/user-img.png') }}" alt="">
                                            @else
                                                <img src="{{ asset($UFM->featuredImage) }}" alt="">
                                            @endif
                                        @else
                                            <img src="{{ asset('assets/images/user-img.png') }}" alt="">
                                        @endif


                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @yield('contents')

            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"
        integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var path = window.location.pathname; // Get the current path
            var navItems = document.querySelectorAll('.navigat-items'); // Select all navigat-items

            navItems.forEach(function(item) {
                var href = item.getAttribute('href'); // Get the href of each item
                var hrefPath = new URL(href).pathname; // Extract the pathname from the href


                // Check if the current path matches the href path
                if (path === hrefPath) {
                    item.classList.add('active'); // Add active class if it matches
                }
            });
        });
    </script>




    <script>
        var xValuesJS = ["Mon", "Tue", "Wed", "Thu", "Fri"];
        var yValuesJS = [];

        fetch('{{ route("alertchart") }}')
            .then(response => response.json())
            .then(data => {
                xValuesJS.forEach(day => {
                    // Convert abbreviated day names to full day names
                    let fullDayName = {
                        "Mon": "Monday",
                        "Tue": "Tuesday",
                        "Wed": "Wednesday",
                        "Thu": "Thursday",
                        "Fri": "Friday"
                    } [day];

                    // Push the count for each day (0 if no alerts for that day)
                    yValues.push(data[fullDayName] || 0);
                });


            })
            .catch(error => console.error('Error fetching data:', error));
        console.log(yValuesJS);
        var xValues = xValuesJS;
        var yValues = yValuesJS;
        var barColors = ["#004FFF", "#004FFF", "#004FFF", "#004FFF", "#004FFF"];

        new Chart("myChart", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                    borderWidth: 1, // Optional: set the width of the border
                    borderRadius: 20 // Set border-radius here
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                }
            }
        });
    </script>

    <script>
        // Get the current URL path
        const currentPath = window.location.pathname;

        // Select the parent <ul> element
        const navList = document.getElementById('navigation');

        // Check if the current path is '/check-points'
        if (currentPath === '/check-points') {
            // Add 'active' class to the <ul>
            navList.classList.add('active');
        }
    </script>



    <!-- JavaScript to Handle Icon Population and Selection -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // List of 300+ Font Awesome icons to display
            const allIcons = [
                "fa-solid fa-house", "fa-solid fa-car", "fa-solid fa-cog", "fa-solid fa-bell",
                "fa-solid fa-user",
                "fa-solid fa-tree", "fa-solid fa-camera", "fa-solid fa-heart", "fa-solid fa-map",
                "fa-solid fa-shield",
                "fa-solid fa-home", "fa-solid fa-check", "fa-solid fa-plus", "fa-solid fa-envelope",
                "fa-solid fa-lock",
                "fa-solid fa-key", "fa-solid fa-trash", "fa-solid fa-pen", "fa-solid fa-pencil-alt",
                "fa-solid fa-search",
                "fa-solid fa-comment", "fa-solid fa-bug", "fa-solid fa-rocket", "fa-solid fa-code",
                "fa-solid fa-server",
                "fa-solid fa-music", "fa-solid fa-headphones", "fa-solid fa-book", "fa-solid fa-bookmark",
                "fa-solid fa-phone",
                "fa-solid fa-laptop", "fa-solid fa-desktop", "fa-solid fa-tablet", "fa-solid fa-mobile",
                "fa-solid fa-sitemap",
                "fa-solid fa-umbrella", "fa-solid fa-graduation-cap", "fa-solid fa-lightbulb",
                "fa-solid fa-paint-brush", "fa-solid fa-palette",
                "fa-solid fa-wallet", "fa-solid fa-shopping-cart", "fa-solid fa-box", "fa-solid fa-certificate",
                "fa-solid fa-coffee",
                "fa-solid fa-hammer", "fa-solid fa-hard-hat", "fa-solid fa-wrench", "fa-solid fa-briefcase",
                "fa-solid fa-globe",
                "fa-solid fa-glass", "fa-solid fa-gift", "fa-solid fa-pizza-slice", "fa-solid fa-birthday-cake",
                "fa-solid fa-bomb",
                "fa-solid fa-dice", "fa-solid fa-gamepad", "fa-solid fa-keyboard", "fa-solid fa-microchip",
                "fa-solid fa-tv",
                "fa-solid fa-video", "fa-solid fa-mouse", "fa-solid fa-project-diagram",
                "fa-solid fa-puzzle-piece", "fa-solid fa-robot",
                "fa-solid fa-screwdriver", "fa-solid fa-shapes", "fa-solid fa-sitemap",
                "fa-solid fa-traffic-light", "fa-solid fa-trophy",
                "fa-solid fa-tools", "fa-solid fa-truck", "fa-solid fa-warehouse", "fa-solid fa-weight-hanging",
                "fa-solid fa-window-maximize",
                "fa-solid fa-window-minimize", "fa-solid fa-wrench", "fa-regular fa-address-book",
                "fa-regular fa-address-card",
                "fa-regular fa-angry", "fa-regular fa-arrow-alt-circle-down",
                "fa-regular fa-arrow-alt-circle-left",
                "fa-regular fa-arrow-alt-circle-right", "fa-regular fa-arrow-alt-circle-up",
                "fa-regular fa-bell-slash",
                "fa-regular fa-calendar-alt", "fa-regular fa-calendar-check", "fa-regular fa-calendar-minus",
                "fa-regular fa-calendar-plus", "fa-regular fa-calendar-times",
                "fa-regular fa-caret-square-down",
                "fa-regular fa-caret-square-left", "fa-regular fa-caret-square-right",
                "fa-regular fa-caret-square-up",
                "fa-regular fa-chart-bar", "fa-regular fa-check-circle", "fa-regular fa-check-square",
                "fa-regular fa-circle",
                "fa-regular fa-clipboard", "fa-regular fa-clock", "fa-regular fa-clone",
                "fa-regular fa-closed-captioning",
                "fa-regular fa-comment-alt", "fa-regular fa-comment-dots", "fa-regular fa-comments",
                "fa-regular fa-compass",
                "fa-regular fa-copy", "fa-regular fa-credit-card", "fa-regular fa-dizzy",
                "fa-regular fa-dot-circle",
                "fa-regular fa-edit", "fa-regular fa-envelope-open", "fa-regular fa-eye-slash",
                "fa-regular fa-file-alt",
                "fa-regular fa-file-archive", "fa-regular fa-file-audio", "fa-regular fa-file-code",
                "fa-regular fa-file-excel",
                "fa-regular fa-file-image", "fa-regular fa-file-pdf", "fa-regular fa-file-powerpoint",
                "fa-regular fa-file-video",
                "fa-regular fa-file-word", "fa-regular fa-flag", "fa-regular fa-flushed",
                "fa-regular fa-folder-minus",
                "fa-regular fa-folder-plus", "fa-regular fa-frown-open", "fa-regular fa-futbol",
                "fa-regular fa-gem",
                "fa-regular fa-grimace", "fa-regular fa-grin", "fa-regular fa-grin-alt",
                "fa-regular fa-grin-beam",
                "fa-regular fa-grin-beam-sweat", "fa-regular fa-grin-hearts", "fa-regular fa-grin-squint",
                "fa-regular fa-grin-squint-tears",
                "fa-regular fa-grin-stars", "fa-regular fa-grin-tears", "fa-regular fa-grin-tongue",
                "fa-regular fa-grin-tongue-squint",
                "fa-regular fa-grin-tongue-wink", "fa-regular fa-grin-wink", "fa-regular fa-hand-lizard",
                "fa-regular fa-hand-paper",
                "fa-regular fa-hand-point-down", "fa-regular fa-hand-point-left",
                "fa-regular fa-hand-point-right",
                "fa-regular fa-hand-point-up", "fa-regular fa-hand-scissors", "fa-regular fa-hand-spock",
                "fa-regular fa-handshake", "fa-regular fa-hdd", "fa-regular fa-heart", "fa-regular fa-hospital",
                "fa-regular fa-hourglass", "fa-regular fa-id-badge", "fa-regular fa-id-card",
                "fa-regular fa-image",
                "fa-regular fa-images", "fa-regular fa-keyboard", "fa-regular fa-kiss",
                "fa-regular fa-kiss-beam",
                "fa-regular fa-kiss-wink-heart", "fa-regular fa-laugh", "fa-regular fa-laugh-beam",
                "fa-regular fa-laugh-squint", "fa-regular fa-laugh-wink",
                "fa-brands fa-adobe", "fa-brands fa-airbnb", "fa-brands fa-amazon", "fa-brands fa-android",
                "fa-brands fa-apple", "fa-brands fa-aws", "fa-brands fa-bity", "fa-brands fa-blackberry",
                "fa-brands fa-blogger", "fa-brands fa-bootstrap", "fa-brands fa-btc", "fa-brands fa-centos",
                "fa-brands fa-cloudscale", "fa-brands fa-codepen", "fa-brands fa-css3", "fa-brands fa-drupal",
                "fa-brands fa-dropbox", "fa-brands fa-facebook-f", "fa-brands fa-firefox",
                "fa-brands fa-github-alt",
                "fa-brands fa-google-drive", "fa-brands fa-google-play", "fa-brands fa-hacker-news",
                "fa-brands fa-html5",
                "fa-brands fa-java", "fa-brands fa-js", "fa-brands fa-kickstarter", "fa-brands fa-linkedin-in",
                "fa-brands fa-linux", "fa-brands fa-magento", "fa-brands fa-microsoft", "fa-brands fa-node",
                "fa-brands fa-php", "fa-brands fa-python", "fa-brands fa-reddit", "fa-brands fa-safari",
                "fa-brands fa-sass", "fa-brands fa-skype", "fa-brands fa-slack", "fa-brands fa-spotify",
                "fa-brands fa-stack-exchange", "fa-brands fa-stack-overflow", "fa-brands fa-steam",
                "fa-brands fa-studiovinari", "fa-brands fa-telegram", "fa-brands fa-trello",
                "fa-brands fa-tumblr",
                "fa-brands fa-twitter-square", "fa-brands fa-viber", "fa-brands fa-vimeo", "fa-brands fa-vk",
                "fa-brands fa-whatsapp", "fa-brands fa-wikipedia-w", "fa-brands fa-wordpress",
                "fa-brands fa-yahoo",
                "fa-brands fa-yandex", "fa-brands fa-youtube"
            ];

            // Container where icons will be displayed
            const iconContainer = document.getElementById('iconContainer');

            allIcons.forEach(iconClass => {
                const iconElement = document.createElement('i');
                iconElement.className = `${iconClass} fa-2x icon-option`;
                iconElement.style.cursor = 'pointer';

                // Add onclick event listener
                iconElement.onclick = function() {
                    // Your click event logic here
                    alert(`Icon ${iconClass} clicked!`);
                    const iconModal = bootstrap.Modal.getInstance(document.getElementById('iconModal'));
                    iconModal.hide();
                    // You can add more functionality as needed
                };

                iconContainer.appendChild(iconElement);
            });

            const svgIcons = document.querySelectorAll('.svg-inline--fa');

            svgIcons.forEach(iconElement => {
                // Add onclick event listener
                iconElement.onclick = function() {
                    // Get the 'data-icon' attribute value
                    const dataIconValue = iconElement.getAttribute('data-icon');

                    // Your click event logic here
                    console.log(`Icon clicked! Data-icon value: ${dataIconValue}`);

                    // You can add more functionality as needed
                };
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const listItems = document.querySelectorAll('#work-site-list li');

            listItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    listItems.forEach(i => i.classList.remove('active'));

                    // Add active class to the clicked item
                    this.classList.add('active');
                });
            });
        });
    </script>





    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the view buttons
            const viewButtons = document.querySelectorAll('.view-image-btn');

            // Iterate over each button to attach event listener
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Get the image source from the data attribute
                    const imgSrc = this.getAttribute('data-image-src');

                    // Set the source of the modal image
                    const lightboxImage = document.getElementById('lightboxImage');
                    lightboxImage.setAttribute('src', imgSrc);
                });
            });
        });
    </script>



</body>

</html>
