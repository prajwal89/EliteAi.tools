<div class="rounded-lg flex-1 px-2 text-lg self-start">

    <div class="flex gap-2 items-center py-2">
        {{-- <span>Features:</span> --}}
        <div title="Pricing type"
            class="flex items-center cursor-pointer gap-0.5 bg-gray-100 shadow-sm text-black px-2 py-0.5 rounded-full">
            {{-- <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg> --}}
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M13 3.5C13 2.94772 12.5523 2.5 12 2.5C11.4477 2.5 11 2.94772 11 3.5V4.0592C9.82995 4.19942 8.75336 4.58509 7.89614 5.1772C6.79552 5.93745 6 7.09027 6 8.5C6 9.77399 6.49167 10.9571 7.5778 11.7926C8.43438 12.4515 9.58764 12.8385 11 12.959V17.9219C10.2161 17.7963 9.54046 17.5279 9.03281 17.1772C8.32378 16.6874 8 16.0903 8 15.5C8 14.9477 7.55228 14.5 7 14.5C6.44772 14.5 6 14.9477 6 15.5C6 16.9097 6.79552 18.0626 7.89614 18.8228C8.75336 19.4149 9.82995 19.8006 11 19.9408V20.5C11 21.0523 11.4477 21.5 12 21.5C12.5523 21.5 13 21.0523 13 20.5V19.9435C14.1622 19.8101 15.2376 19.4425 16.0974 18.8585C17.2122 18.1013 18 16.9436 18 15.5C18 14.1934 17.5144 13.0022 16.4158 12.1712C15.557 11.5216 14.4039 11.1534 13 11.039V6.07813C13.7839 6.20366 14.4596 6.47214 14.9672 6.82279C15.6762 7.31255 16 7.90973 16 8.5C16 9.05228 16.4477 9.5 17 9.5C17.5523 9.5 18 9.05228 18 8.5C18 7.09027 17.2045 5.93745 16.1039 5.17721C15.2467 4.58508 14.1701 4.19941 13 4.0592V3.5ZM11 6.07814C10.2161 6.20367 9.54046 6.47215 9.03281 6.8228C8.32378 7.31255 8 7.90973 8 8.5C8 9.22601 8.25834 9.79286 8.79722 10.2074C9.24297 10.5503 9.94692 10.8384 11 10.9502V6.07814ZM13 13.047V17.9263C13.7911 17.8064 14.4682 17.5474 14.9737 17.204C15.6685 16.7321 16 16.1398 16 15.5C16 14.7232 15.7356 14.1644 15.2093 13.7663C14.7658 13.4309 14.0616 13.1537 13 13.047Z"
                    fill="currentColor" />
            </svg>
            <span>{{ $tool->pricing_type }}</span>
        </div>
        @if ($tool->has_api)
            <div title="Support for API"
                class="flex items-center cursor-pointer gap-0.5 bg-gray-100 shadow-sm text-black px-2 py-0.5 rounded-full">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M17 9V12C17 14.7614 14.7614 17 12 17M7 9V12C7 14.7614 9.23858 17 12 17M12 17V21M8 3V6M16 3V6M5 9H19"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>API</span>
            </div>
        @endif
        @if ($tool->monthly_subscription_starts_from)
            <div
                class="flex items-center cursor-pointer gap-0.5 bg-gray-100 shadow-sm text-black px-2 py-0.5 rounded-full">
                From <strong class="text-xl">{{ $tool->monthly_subscription_starts_from }}$</strong>
            </div>
        @endif
        @if ($tool->pay_once_price_starts_from)
            <div
                class="flex items-center cursor-pointer gap-0.5 bg-gray-100 shadow-sm text-black px-2 py-0.5 rounded-full">
                Pay Once <strong class="text-xl">{{ $tool->pay_once_price_starts_from }}$</strong>
            </div>
        @endif
    </div>

    <p class="py-2">
        <strong>Home:</strong>
        <a class="text-primary-600" href="{{ $tool->home_page_url }}">{{ $tool->home_page_url }}</a>
    </p>

    @if (!$tool->categories->isEmpty())
        <div class="flex gap-2 py-2">
            <strong>Categories:</strong>
            <ul class="flex flex-wrap gap-2">
                @foreach ($tool->categories as $category)
                    <li>
                        <a class="flex items-center gap-2 text-gray-700 hover:text-primary-600"
                            href="{{ route('category.show', ['category' => $category->slug]) }}">
                            <span>{{ $category->name }}{{ $loop->last ? '' : ',' }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (
        !empty($tool->instagram_id) ||
            !empty($tool->twitter_id) ||
            !empty($tool->tiktok_id) ||
            !empty($tool->youtube_channel_id) ||
            !empty($tool->youtube_handle_id) ||
            !empty($tool->facebook_id) ||
            !empty($tool->linkedin_id) ||
            !empty($tool->behance_id) ||
            !empty($tool->pinterest_id) ||
            !empty($tool->slack_app_id) ||
            !empty($tool->slack_channel_id) ||
            !empty($tool->figma_plugin_id) ||
            !empty($tool->dribbble_id) ||
            !empty($tool->discord_channel_invite_id) ||
            !empty($tool->github_repository_path) ||
            !empty($tool->linkedin_company_id))
        <div class="py-2 flex items-center gap-4">
            <strong>Social:</strong>
            <ul class="flex flex-wrap items-center justify-center gap-2">

                @if (!empty($tool->tiktok_id))
                    <li>
                        <a href="https://www.tiktok.com/{{ e('@') }}{{ $tool->tiktok_id }}" target="_blank"
                            title="Tiktok"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 32 32" version="1.1">
                                <title>tiktok</title>
                                <path
                                    d="M16.656 1.029c1.637-0.025 3.262-0.012 4.886-0.025 0.054 2.031 0.878 3.859 2.189 5.213l-0.002-0.002c1.411 1.271 3.247 2.095 5.271 2.235l0.028 0.002v5.036c-1.912-0.048-3.71-0.489-5.331-1.247l0.082 0.034c-0.784-0.377-1.447-0.764-2.077-1.196l0.052 0.034c-0.012 3.649 0.012 7.298-0.025 10.934-0.103 1.853-0.719 3.543-1.707 4.954l0.020-0.031c-1.652 2.366-4.328 3.919-7.371 4.011l-0.014 0c-0.123 0.006-0.268 0.009-0.414 0.009-1.73 0-3.347-0.482-4.725-1.319l0.040 0.023c-2.508-1.509-4.238-4.091-4.558-7.094l-0.004-0.041c-0.025-0.625-0.037-1.25-0.012-1.862 0.49-4.779 4.494-8.476 9.361-8.476 0.547 0 1.083 0.047 1.604 0.136l-0.056-0.008c0.025 1.849-0.050 3.699-0.050 5.548-0.423-0.153-0.911-0.242-1.42-0.242-1.868 0-3.457 1.194-4.045 2.861l-0.009 0.030c-0.133 0.427-0.21 0.918-0.21 1.426 0 0.206 0.013 0.41 0.037 0.61l-0.002-0.024c0.332 2.046 2.086 3.59 4.201 3.59 0.061 0 0.121-0.001 0.181-0.004l-0.009 0c1.463-0.044 2.733-0.831 3.451-1.994l0.010-0.018c0.267-0.372 0.45-0.822 0.511-1.311l0.001-0.014c0.125-2.237 0.075-4.461 0.087-6.698 0.012-5.036-0.012-10.060 0.025-15.083z" />
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->twitter_id))
                    <li>
                        <a href="https://twitter.com/{{ $tool->twitter_id }}" target="_blank" title="Twitter"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="" style="height: 18px; width:24px;" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid"
                                viewBox="0 0 31.812 26">
                                <path
                                    d="M20.877,2.000 C22.519,2.000 24.382,2.652 25.426,3.738 C26.724,3.486 27.949,3.025 29.050,2.386 C28.625,3.687 27.718,4.779 26.540,5.469 C27.693,5.332 28.797,5.035 29.820,4.590 C29.054,5.707 28.087,6.690 26.971,7.477 C26.981,7.715 26.987,7.955 26.987,8.195 C26.987,15.562 21.445,24.000 10.939,24.000 C7.715,24.000 4.507,23.133 1.982,21.551 C2.428,21.605 2.883,21.631 3.343,21.631 C6.019,21.631 8.482,20.740 10.439,19.242 C7.937,19.199 5.827,17.586 5.103,15.373 C5.450,15.437 5.810,15.473 6.178,15.473 C6.696,15.473 7.203,15.406 7.681,15.277 C5.068,14.768 3.100,12.514 3.100,9.813 C3.100,9.787 3.100,9.764 3.100,9.740 C3.871,10.158 4.750,10.410 5.687,10.440 C4.154,9.437 3.147,7.734 3.147,5.799 C3.147,4.777 3.428,3.818 3.919,2.998 C6.735,6.367 10.945,8.588 15.693,8.822 C15.594,8.414 15.543,7.984 15.543,7.553 C15.543,4.473 17.721,2.000 20.877,2.000 M29.820,4.590 L29.825,4.590 M20.877,-0.000 C17.033,-0.000 14.060,2.753 13.614,6.552 C10.425,5.905 7.524,4.204 5.440,1.711 C5.061,1.257 4.503,0.998 3.919,0.998 C3.867,0.998 3.815,1.000 3.763,1.004 C3.123,1.055 2.547,1.413 2.216,1.966 C1.525,3.122 1.159,4.447 1.159,5.799 C1.159,6.700 1.321,7.579 1.625,8.400 C1.300,8.762 1.113,9.238 1.113,9.740 L1.113,9.813 C1.113,11.772 1.882,13.589 3.160,14.952 C3.087,15.294 3.103,15.655 3.215,15.998 C3.657,17.348 4.459,18.510 5.499,19.396 C4.800,19.552 4.079,19.631 3.343,19.631 C2.954,19.631 2.577,19.609 2.222,19.565 C2.141,19.556 2.061,19.551 1.981,19.551 C1.148,19.551 0.391,20.078 0.108,20.886 C-0.202,21.770 0.140,22.753 0.932,23.249 C3.764,25.023 7.318,26.000 10.939,26.000 C17.778,26.000 22.025,22.843 24.383,20.195 C27.243,16.984 28.907,12.718 28.972,8.455 C29.899,7.682 30.717,6.790 31.410,5.792 C31.661,5.458 31.810,5.041 31.810,4.590 C31.810,3.909 31.473,3.308 30.958,2.946 C31.181,2.176 30.925,1.342 30.303,0.833 C29.940,0.537 29.496,0.386 29.049,0.386 C28.708,0.386 28.365,0.474 28.056,0.654 C27.391,1.040 26.680,1.344 25.931,1.562 C24.555,0.592 22.688,-0.000 20.877,-0.000 L20.877,-0.000 Z" />
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->instagram_id))
                    <li>
                        <a href="https://www.instagram.com/{{ $tool->instagram_id }}" target="_blank" title="Instagram"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->linkedin_id))
                    <li>
                        <a href="https://www.linkedin.com/in/{{ $tool->linkedin_id }}" target="_blank" title="LinkedIn"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24">
                                <path
                                    d="M6.5 8C7.32843 8 8 7.32843 8 6.5C8 5.67157 7.32843 5 6.5 5C5.67157 5 5 5.67157 5 6.5C5 7.32843 5.67157 8 6.5 8Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M5 10C5 9.44772 5.44772 9 6 9H7C7.55228 9 8 9.44771 8 10V18C8 18.5523 7.55228 19 7 19H6C5.44772 19 5 18.5523 5 18V10Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M11 19H12C12.5523 19 13 18.5523 13 18V13.5C13 12 16 11 16 13V18.0004C16 18.5527 16.4477 19 17 19H18C18.5523 19 19 18.5523 19 18V12C19 10 17.5 9 15.5 9C13.5 9 13 10.5 13 10.5V10C13 9.44771 12.5523 9 12 9H11C10.4477 9 10 9.44772 10 10V18C10 18.5523 10.4477 19 11 19Z"
                                    fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20 1C21.6569 1 23 2.34315 23 4V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H20ZM20 3C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3H20Z"
                                    fill="currentColor"></path>
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->linkedin_company_id))
                    <li>
                        <a href="https://www.linkedin.com/company/{{ $tool->linkedin_company_id }}" target="_blank"
                            title="LinkedIn"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24">
                                <path
                                    d="M6.5 8C7.32843 8 8 7.32843 8 6.5C8 5.67157 7.32843 5 6.5 5C5.67157 5 5 5.67157 5 6.5C5 7.32843 5.67157 8 6.5 8Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M5 10C5 9.44772 5.44772 9 6 9H7C7.55228 9 8 9.44771 8 10V18C8 18.5523 7.55228 19 7 19H6C5.44772 19 5 18.5523 5 18V10Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M11 19H12C12.5523 19 13 18.5523 13 18V13.5C13 12 16 11 16 13V18.0004C16 18.5527 16.4477 19 17 19H18C18.5523 19 19 18.5523 19 18V12C19 10 17.5 9 15.5 9C13.5 9 13 10.5 13 10.5V10C13 9.44771 12.5523 9 12 9H11C10.4477 9 10 9.44772 10 10V18C10 18.5523 10.4477 19 11 19Z"
                                    fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20 1C21.6569 1 23 2.34315 23 4V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H20ZM20 3C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3H20Z"
                                    fill="currentColor"></path>
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->facebook_id))
                    <li>
                        <a href="https://www.facebook.com/profile.php?id={{ $tool->facebook_id }}" target="_blank"
                            title="Facebook profile"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20 1C21.6569 1 23 2.34315 23 4V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H20ZM20 3C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H15V13.9999H17.0762C17.5066 13.9999 17.8887 13.7245 18.0249 13.3161L18.4679 11.9871C18.6298 11.5014 18.2683 10.9999 17.7564 10.9999H15V8.99992C15 8.49992 15.5 7.99992 16 7.99992H18C18.5523 7.99992 19 7.5522 19 6.99992V6.31393C19 5.99091 18.7937 5.7013 18.4813 5.61887C17.1705 5.27295 16 5.27295 16 5.27295C13.5 5.27295 12 6.99992 12 8.49992V10.9999H10C9.44772 10.9999 9 11.4476 9 11.9999V12.9999C9 13.5522 9.44771 13.9999 10 13.9999H12V21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3H20Z"
                                    fill="currentColor" />
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->behance_id))
                    <li>
                        <a href="https://www.behance.net/{{ $tool->behance_id }}" target="_blank"
                            title="Behance profile"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M2.5 19C1.67157 19 1 18.3284 1 17.5V6.5C1 5.67157 1.67157 5 2.5 5H8C13 5 13 11.5 10 11.5C13 11.5 14 19 8 19H2.5ZM4.5 11C4.22386 11 4 10.7761 4 10.5V7.5C4 7.22386 4.22386 7 4.5 7H7C7 7 9 7 9 9C9 11 7 11 7 11H4.5ZM4.5 13C4.22386 13 4 13.2239 4 13.5V16.5C4 16.7761 4.22386 17 4.5 17H8C8 17 9.5 17 9.5 15C9.5 13 8 13 8 13H4.5Z"
                                    fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M21.499 14.0034C22.3279 14.0034 23.0212 13.3199 22.8522 12.5085C21.6065 6.52886 12.9128 7.08088 13 14.0034C13.0665 19.2762 20.4344 20.9671 22.6038 16.1898C22.9485 15.4308 22.1747 14.9997 21.5372 14.9997C20.9706 14.9997 20.5313 15.5223 20.1693 15.9582C19.1272 17.2132 15.9628 17.1221 15.5449 14.5142C15.5005 14.2375 15.7304 14.0034 16.0106 14.0034H21.499ZM15.8184 11.9997C15.671 11.9997 15.5758 11.8453 15.6545 11.7207C16.7141 10.0424 19.2614 10.0605 20.3398 11.7189C20.4207 11.8434 20.3257 11.9997 20.1772 11.9997H15.8184Z"
                                    fill="currentColor" />
                                <path
                                    d="M16 6C15.4477 6 15 6.44772 15 7C15 7.55228 15.4477 8 16 8H20C20.5523 8 21 7.55228 21 7C21 6.44772 20.5523 6 20 6H16Z"
                                    fill="currentColor" />
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->dribbble_id))
                    <li>
                        <a href="https://dribbble.com/{{ $tool->dribbble_id }}" target="_blank"
                            title="Dribbble profile"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1C5.92487 1 1 5.92487 1 12C1 18.0751 5.92487 23 12 23ZM5.14386 17.8201C3.81099 16.2515 3.00683 14.2197 3.00683 12L3.00683 11.9978C6.61307 11.9618 9.57567 11.4838 12.2422 10.5779C12.4668 11.0605 12.6847 11.5534 12.8956 12.0564C12.5555 12.1691 12.221 12.2949 11.8918 12.4335C9.24177 13.5489 7.00538 15.4612 5.14386 17.8201ZM6.60614 19.1967C8.10884 20.3248 9.97636 20.9932 12 20.9932C13.2188 20.9932 14.3809 20.7507 15.4409 20.3114C14.9668 18.0368 14.352 15.907 13.6265 13.9217C13.3003 14.0264 12.9807 14.1451 12.6677 14.2768C10.356 15.2499 8.33843 16.9649 6.60614 19.1967ZM15.5924 13.4765C16.2479 15.3019 16.8129 17.2399 17.267 19.2902C19.048 18.0013 20.338 16.0757 20.8032 13.8473C18.9143 13.3589 17.1821 13.2604 15.5924 13.4765ZM14.8575 11.5662C16.754 11.2412 18.7996 11.3067 20.9917 11.8332C20.9578 9.97415 20.3599 8.25291 19.3619 6.8334C17.6358 8.0531 15.9276 9.06168 14.1111 9.85398C14.3687 10.4121 14.6177 10.9829 14.8575 11.5662ZM11.3457 8.76846C8.99734 9.53429 6.39047 9.94463 3.2312 9.9948C3.85725 7.24565 5.74294 4.97565 8.24906 3.82401C9.34941 5.31262 10.3933 6.96064 11.3457 8.76846ZM13.2302 8.05623C14.8876 7.34152 16.4466 6.43089 18.0282 5.32624C16.4333 3.88469 14.3192 3.00683 12 3.00683C11.4014 3.00683 10.8165 3.06531 10.2506 3.17688C11.3103 4.66337 12.3129 6.28992 13.2302 8.05623Z"
                                    fill="currentColor" />
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->pinterest_id))
                    <li>
                        <a href="https://in.pinterest.com/{{ $tool->pinterest_id }}" target="_blank"
                            title="Pinterest Profile"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="w-5 h-5" fill="currentColor" version="1.1" viewBox="0 0 512 512"
                                xml:space="preserve">
                                <g id="7935ec95c421cee6d86eb22ecd12951c">

                                    <path style="display: inline;"
                                        d="M220.646,338.475C207.223,408.825,190.842,476.269,142.3,511.5   c-14.996-106.33,21.994-186.188,39.173-270.971c-29.293-49.292,3.518-148.498,65.285-124.059   c76.001,30.066-65.809,183.279,29.38,202.417c99.405,19.974,139.989-172.476,78.359-235.054   C265.434-6.539,95.253,81.775,116.175,211.161c5.09,31.626,37.765,41.22,13.062,84.884c-57.001-12.65-74.005-57.6-71.822-117.533   c3.53-98.108,88.141-166.787,173.024-176.293c107.34-12.014,208.081,39.398,221.991,140.376   c15.67,113.978-48.442,237.412-163.23,228.529C258.085,368.704,245.023,353.283,220.646,338.475z">
                                    </path>
                                </g>
                            </svg>
                        </a>
                    </li>
                @endif



                @if (!empty($tool->slack_app_id))
                    <li>
                        <a href="https://slack.com/apps/{{ $tool->slack_app_id }}" target="_blank" title="Slack App"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 192"
                                fill="none">
                                <rect width="22" height="64" x="106" y="22" stroke="currentColor"
                                    stroke-width="12" rx="11" />
                                <rect width="22" height="64" x="64" y="106" stroke="currentColor"
                                    stroke-width="12" rx="11" />
                                <rect width="22" height="64" x="170" y="106" stroke="currentColor"
                                    stroke-width="12" rx="11" transform="rotate(90 170 106)" />
                                <rect width="22" height="64" x="86" y="64" stroke="currentColor"
                                    stroke-width="12" rx="11" transform="rotate(90 86 64)" />
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="12"
                                    d="M75 44c-6.075 0-11-4.925-11-11s4.925-11 11-11 11 4.925 11 11v11H75Zm42 104c6.075 0 11 4.925 11 11s-4.925 11-11 11-11-4.925-11-11v-11h11Zm31-73c0-6.075 4.925-11 11-11s11 4.925 11 11-4.925 11-11 11h-11V75ZM44 117c0 6.075-4.925 11-11 11s-11-4.925-11-11 4.925-11 11-11h11v11Z" />
                            </svg>
                        </a>
                    </li>
                @endif
                @if (!empty($tool->slack_channel_id))
                    <li>
                        <a href="https://{{ $tool->slack_channel_id }}.slack.com" target="_blank"
                            title="Slack Channel"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 192"
                                fill="none">
                                <rect width="22" height="64" x="106" y="22" stroke="currentColor"
                                    stroke-width="12" rx="11" />
                                <rect width="22" height="64" x="64" y="106" stroke="currentColor"
                                    stroke-width="12" rx="11" />
                                <rect width="22" height="64" x="170" y="106" stroke="currentColor"
                                    stroke-width="12" rx="11" transform="rotate(90 170 106)" />
                                <rect width="22" height="64" x="86" y="64" stroke="currentColor"
                                    stroke-width="12" rx="11" transform="rotate(90 86 64)" />
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="12"
                                    d="M75 44c-6.075 0-11-4.925-11-11s4.925-11 11-11 11 4.925 11 11v11H75Zm42 104c6.075 0 11 4.925 11 11s-4.925 11-11 11-11-4.925-11-11v-11h11Zm31-73c0-6.075 4.925-11 11-11s11 4.925 11 11-4.925 11-11 11h-11V75ZM44 117c0 6.075-4.925 11-11 11s-11-4.925-11-11 4.925-11 11-11h11v11Z" />
                            </svg>
                        </a>
                    </li>
                @endif
                @if (!empty($tool->figma_plugin_id))
                    <li>
                        <a href="https://www.figma.com/community/plugin/{{ $tool->figma_plugin_id }}" target="_blank"
                            title="Figma plugin"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M12 3H9C7.34315 3 6 4.34315 6 6C6 7.65685 7.34315 9 9 9M12 3V9M12 3H15C16.6569 3 18 4.34315 18 6C18 7.65685 16.6569 9 15 9M12 9H9M12 9H15M12 9V15M9 9C7.34315 9 6 10.3431 6 12C6 13.6569 7.34315 15 9 15M15 9C16.6569 9 18 10.3431 18 12C18 13.6569 16.6569 15 15 15C13.3431 15 12 13.6569 12 12C12 10.3431 13.3431 9 15 9ZM12 15H9M12 15V18C12 19.6569 10.6569 21 9 21C7.34315 21 6 19.6569 6 18C6 16.3431 7.34315 15 9 15"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </a>
                    </li>
                @endif



                @if (!empty($tool->youtube_channel_id))
                    <li>
                        <a href="https://www.youtube.com/channel/{{ $tool->youtube_channel_id }}" target="_blank"
                            title="Youtube channel"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-7 h-7" stroke="currentColor" fill="none"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 -0.5 25 25">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M18.168 19.0028C20.4724 19.0867 22.41 17.29 22.5 14.9858V9.01982C22.41 6.71569 20.4724 4.91893 18.168 5.00282H6.832C4.52763 4.91893 2.58998 6.71569 2.5 9.01982V14.9858C2.58998 17.29 4.52763 19.0867 6.832 19.0028H18.168Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12.008 9.17784L15.169 11.3258C15.3738 11.4454 15.4997 11.6647 15.4997 11.9018C15.4997 12.139 15.3738 12.3583 15.169 12.4778L12.008 14.8278C11.408 15.2348 10.5 14.8878 10.5 14.2518V9.75184C10.5 9.11884 11.409 8.77084 12.008 9.17784Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->youtube_handle_id))
                    <li>
                        <a href="https://www.youtube.com/{{ e('@') }}{{ $tool->youtube_handle_id }}"
                            target="_blank" title="Youtube channel"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-7 h-7" stroke="currentColor" fill="none"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 -0.5 25 25">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M18.168 19.0028C20.4724 19.0867 22.41 17.29 22.5 14.9858V9.01982C22.41 6.71569 20.4724 4.91893 18.168 5.00282H6.832C4.52763 4.91893 2.58998 6.71569 2.5 9.01982V14.9858C2.58998 17.29 4.52763 19.0867 6.832 19.0028H18.168Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12.008 9.17784L15.169 11.3258C15.3738 11.4454 15.4997 11.6647 15.4997 11.9018C15.4997 12.139 15.3738 12.3583 15.169 12.4778L12.008 14.8278C11.408 15.2348 10.5 14.8878 10.5 14.2518V9.75184C10.5 9.11884 11.409 8.77084 12.008 9.17784Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->discord_channel_invite_id))
                    <li>
                        <a href="https://discord.com/invite/{{ $tool->discord_channel_invite_id }}" target="_blank"
                            title="Discord channel invite"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 192"
                                fill="none">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="12"
                                    d="m68 138-8 16c-10.19-4.246-20.742-8.492-31.96-15.8-3.912-2.549-6.284-6.88-6.378-11.548-.488-23.964 5.134-48.056 19.369-73.528 1.863-3.334 4.967-5.778 8.567-7.056C58.186 43.02 64.016 40.664 74 39l6 11s6-2 16-2 16 2 16 2l6-11c9.984 1.664 15.814 4.02 24.402 7.068 3.6 1.278 6.704 3.722 8.567 7.056 14.235 25.472 19.857 49.564 19.37 73.528-.095 4.668-2.467 8.999-6.379 11.548-11.218 7.308-21.769 11.554-31.96 15.8l-8-16m-68-8s20 10 40 10 40-10 40-10" />
                                <ellipse cx="71" cy="101" fill="currentColor" rx="13"
                                    ry="15" />
                                <ellipse cx="121" cy="101" fill="currentColor" rx="13"
                                    ry="15" />
                            </svg>
                        </a>
                    </li>
                @endif

                @if (!empty($tool->github_repository_path))
                    <li>
                        <a href="https://github.com/{{ $tool->github_repository_path }}" target="_blank"
                            title="Discord channel invite"
                            class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 192"
                                fill="none">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="12"
                                    d="M120.755 170c.03-4.669.059-20.874.059-27.29 0-9.272-3.167-15.339-6.719-18.41 22.051-2.464 45.201-10.863 45.201-49.067 0-10.855-3.824-19.735-10.175-26.683 1.017-2.516 4.413-12.63-.987-26.32 0 0-8.296-2.672-27.202 10.204-7.912-2.213-16.371-3.308-24.784-3.352-8.414.044-16.872 1.14-24.785 3.352C52.457 19.558 44.162 22.23 44.162 22.23c-5.4 13.69-2.004 23.804-.987 26.32C36.824 55.498 33 64.378 33 75.233c0 38.204 23.149 46.603 45.2 49.067-3.551 3.071-6.719 9.138-6.719 18.41 0 6.416.03 22.621.059 27.29M27 130c9.939.703 15.67 9.735 15.67 9.735 8.834 15.199 23.178 10.803 28.815 8.265" />
                            </svg>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    @endif

    @if (
        !empty($tool->android_app_id) ||
            !empty($tool->ios_app_id) ||
            !empty($tool->window_store_id) ||
            !empty($tool->mac_store_id))
        <div class="flex items-center flex-wrap gap-1 py-2">
            <strong>Apps:</strong>
            @if (!empty($tool->android_app_id))
                <a target="_blank" href="https://play.google.com/store/apps/details?id={{ $tool->android_app_id }}">
                    <img class="h-8" src="{{ asset('/images/social/google-play-store.svg') }}"
                        alt="Playstore Logo">
                </a>
            @endif
            @if (!empty($tool->ios_app_id))
                <a target="_blank" href="https://apps.apple.com/app/{{ $tool->ios_app_id }}">
                    <img class="h-8" src="{{ asset('/images/social/ios-app-store.svg') }}" alt="AppStore Logo">
                </a>
            @endif
            @if (!empty($tool->window_store_id))
                <a target="_blank" href="https://www.microsoft.com/store/apps/{{ $tool->window_store_id }}">
                    <img class="h-16" style="height: 96px"
                        src="{{ asset('/images/social/windows-app-store.svg') }}" alt="Windows store logo">
                </a>
            @endif
            @if (!empty($tool->mac_store_id))
                <a target="_blank" href="https://apps.apple.com/app/{{ $tool->mac_store_id }}?platform=mac">
                    <img class="h-8" src="{{ asset('/images/social/mac-app-store.svg') }}" alt="Mac store logo">
                </a>
            @endif
        </div>
    @endif

    @if (!empty($tool->firefox_extension_id) || !empty($tool->chrome_extension_id))
        <div class="flex items-center gap-2 py-2">
            <strong>Extension:</strong>
            @if (!empty($tool->chrome_extension_id))
                <a target="_blank" class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400"
                    href="https://chrome.google.com/webstore/detail/{{ $tool->chrome_extension_id }}">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                            d="M11.8195 6.9C14.6795 6.28 17.5795 6.19 20.4495 6.66C18.6795 3.86 15.5595 2 11.9995 2C8.88953 2 6.10953 3.42 4.26953 5.65C4.70953 6.95 5.26953 8.19 5.93953 9.37C6.31953 10.05 7.28953 10.02 7.67953 9.35C8.52953 7.92 10.0595 6.96 11.8195 6.9Z"
                            fill="currentColor" />
                        <path
                            d="M7.72002 14.7214C5.72002 12.5814 4.16002 10.1414 3.09002 7.44141C1.59002 10.4014 1.60002 14.0314 3.43002 17.0814C5.03002 19.7514 7.67002 21.4114 10.53 21.8414C11.42 20.7914 12.2 19.6714 12.87 18.5014C13.26 17.8214 12.73 17.0114 11.95 17.0114C10.28 17.0214 8.67002 16.2014 7.72002 14.7214Z"
                            fill="currentColor" />
                        <path
                            d="M8.57031 11.9982C8.57031 12.6082 8.72031 13.1782 9.03031 13.7182C9.64031 14.7682 10.7703 15.4282 11.9903 15.4282C13.2103 15.4282 14.3503 14.7682 14.9503 13.7182C15.2603 13.1782 15.4203 12.6082 15.4203 11.9982C15.4203 10.1082 13.8803 8.57817 11.9903 8.57817C10.1103 8.56817 8.57031 10.1082 8.57031 11.9982Z"
                            fill="currentColor" />
                        <path
                            d="M21.3402 8.41931C19.9702 8.12931 18.5802 7.96931 17.2002 7.94931C16.4102 7.93931 15.9602 8.79931 16.3502 9.48931C16.7502 10.1993 16.9802 11.0193 16.9802 11.8893C16.9802 12.7293 16.7602 13.5593 16.3502 14.2993C15.4102 17.1793 14.0102 19.7393 12.1602 21.9993C17.6102 21.9093 22.0002 17.4693 22.0002 11.9993C22.0002 10.7393 21.7702 9.52931 21.3402 8.41931Z"
                            fill="currentColor" />
                    </svg>
                </a>
            @endif
            @if (!empty($tool->firefox_extension_id))
                <a target="_blank" class="text-gray-600 hover:text-gray-900 dark:hover:text-white dark:text-gray-400"
                    href="https://addons.mozilla.org/en-US/firefox/addon/{{ $tool->firefox_extension_id }}">
                    <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g>
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path fill-rule="nonzero"
                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12c0-1.464.314-2.854.88-4.106.466-.939 1.233-1.874 1.85-2.194-.653 1.283-.973 2.54-1.04 3.383.454-1.5 1.315-2.757 2.52-3.644 2.066-1.519 4.848-1.587 5.956-.62-2.056.707-4.296 3.548-3.803 6.876.08.55.245 1.084.489 1.582-.384-1.01-.418-2.433.202-3.358.692-1.03 1.678-1.248 2.206-1.136-.208-.044-.668.836-.736.991-.173.394-.259.82-.251 1.25a3.395 3.395 0 0 0 1.03 2.38c1.922 1.871 5.023 1.135 6.412-1.002.953-1.471 1.069-3.968-.155-5.952a6.915 6.915 0 0 0-1.084-1.32c-1.85-1.766-4.48-2.57-6.982-2.205-1.106.177-2.047.496-2.824.956C7.755 2.798 9.91 2 12 2z" />
                        </g>
                    </svg>
                </a>
            @endif
        </div>
    @endif

    @if (!$tool->tags->isEmpty())
        <ul class="flex gap-2 py-2 flex-wrap gap-x-2">
            @foreach ($tool->tags as $key => $tag)
                {{-- @if ($key > 3) @break @endif --}}
                <li>
                    <p class="flex items-center gap-2 text-gray-700">
                        @if ($tag->tools_count > 2)
                            {{-- todo should i include this (Low quality page) --}}
                            <a class="text-gray-800"
                                href="{{ route('tag.show', ['tag' => $tag->slug]) }}">#{{ $tag->name }}</a>
                        @else
                            #{{ $tag->name }}
                        @endif
                    </p>
                </li>
            @endforeach
        </ul>
    @endif

</div>
