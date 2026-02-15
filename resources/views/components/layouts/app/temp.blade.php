<main class="dashboard-main">
    <div class="navbar-header border-b border-neutral-200 dark:border-neutral-600">
        <div class="flex items-center justify-between">
            <div class="col-auto">
                <div class="flex flex-wrap items-center gap-[16px]">
                    <button type="button" class="sidebar-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon non-active"></iconify-icon>
                        <iconify-icon icon="iconoir:arrow-right" class="icon active"></iconify-icon>
                    </button>
                    <button type="button" class="sidebar-mobile-toggle d-flex !leading-[0]">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon !text-[30px]"></iconify-icon>
                    </button>
                    <form class="navbar-search">
                        <input type="text" name="search" placeholder="Search">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>

                </div>
            </div>
            <div class="col-auto">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" id="theme-toggle"
                        class="w-10 h-10 bg-neutral-200 dark:bg-neutral-700 dark:text-white rounded-full flex justify-center items-center">
                        <span id="theme-toggle-dark-icon" class="hidden">
                            <i class="ri-sun-line"></i>
                        </span>
                        <span id="theme-toggle-light-icon" class="hidden">
                            <i class="ri-moon-line"></i>
                        </span>
                    </button>

                    <!-- Language Dropdown Start  -->
                    <div class="hidden sm:inline-block">
                        <button data-dropdown-toggle="dropdownInformation"
                            class="has-indicator w-10 h-10 bg-neutral-200 dark:bg-neutral-700 dark:text-white rounded-full flex justify-center items-center"
                            type="button">
                            <img src="assets/images/lang-flag.png" alt="image"
                                class="w-6 h-6 object-cover rounded-full">
                        </button>
                        <div id="dropdownInformation"
                            class="z-10 hidden bg-white dark:bg-neutral-700 rounded-lg shadow-lg dropdown-menu-sm p-3">
                            <div
                                class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 mb-4 flex items-center justify-between gap-2">
                                <div>
                                    <h6 class="text-lg text-neutral-900 font-semibold mb-0">Choose Your Language</h6>
                                </div>
                            </div>

                            <div class="max-h-[400px] overflow-y-auto scroll-sm pe-2">
                                <div class="mt-4 flex flex-col gap-4">

                                    <div class="form-check style-check flex items-center justify-between">
                                        <label class="form-check-label line-height-1 font-medium text-secondary-light"
                                            for="english">
                                            <span
                                                class="text-black hover-bXg-transparent hover-text-primary flex items-center gap-3">
                                                <img src="assets/images/flags/flag1.png" alt=""
                                                    class="w-9 h-9 bg-success-subtle text-success-600 rounded-full shrink-0">
                                                <span class="text-base font-semibold mb-0">English</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input rounded-full" name="language" type="radio"
                                            id="english">
                                    </div>

                                    <div class="form-check style-check flex items-center justify-between">
                                        <label class="form-check-label line-height-1 font-medium text-secondary-light"
                                            for="Japan">
                                            <span
                                                class="text-black hover-bXg-transparent hover-text-primary flex items-center gap-3">
                                                <img src="assets/images/flags/flag2.png" alt=""
                                                    class="w-9 h-9 bg-success-subtle text-success-600 rounded-full shrink-0">
                                                <span class="text-base font-semibold mb-0">Japan</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input rounded-full" name="language" type="radio"
                                            id="Japan">
                                    </div>

                                    <div class="form-check style-check flex items-center justify-between">
                                        <label class="form-check-label line-height-1 font-medium text-secondary-light"
                                            for="Franch">
                                            <span
                                                class="text-black hover-bXg-transparent hover-text-primary flex items-center gap-3">
                                                <img src="assets/images/flags/flag3.png" alt=""
                                                    class="w-9 h-9 bg-success-subtle text-success-600 rounded-full shrink-0">
                                                <span class="text-base font-semibold mb-0">Franch</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input rounded-full" name="language" type="radio"
                                            id="Franch">
                                    </div>

                                    <div class="form-check style-check flex items-center justify-between">
                                        <label class="form-check-label line-height-1 font-medium text-secondary-light"
                                            for="Germany">
                                            <span
                                                class="text-black hover-bXg-transparent hover-text-primary flex items-center gap-3">
                                                <img src="assets/images/flags/flag4.png" alt=""
                                                    class="w-9 h-9 bg-success-subtle text-success-600 rounded-full shrink-0">
                                                <span class="text-base font-semibold mb-0">Germany</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input rounded-full" name="language" type="radio"
                                            id="Germany">
                                    </div>

                                    <div class="form-check style-check flex items-center justify-between">
                                        <label class="form-check-label line-height-1 font-medium text-secondary-light"
                                            for="SouthKoria">
                                            <span
                                                class="text-black hover-bXg-transparent hover-text-primary flex items-center gap-3">
                                                <img src="assets/images/flags/flag5.png" alt=""
                                                    class="w-9 h-9 bg-success-subtle text-success-600 rounded-full shrink-0">
                                                <span class="text-base font-semibold mb-0">South Koria</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input rounded-full" name="language" type="radio"
                                            id="SouthKoria">
                                    </div>

                                    <div class="form-check style-check flex items-center justify-between">
                                        <label class="form-check-label line-height-1 font-medium text-secondary-light"
                                            for="Bangladesh">
                                            <span
                                                class="text-black hover-bXg-transparent hover-text-primary flex items-center gap-3">
                                                <img src="assets/images/flags/flag6.png" alt=""
                                                    class="w-9 h-9 bg-success-subtle text-success-600 rounded-full shrink-0">
                                                <span class="text-base font-semibold mb-0">Bangladesh</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input rounded-full" name="language" type="radio"
                                            id="Bangladesh">
                                    </div>

                                    <div class="form-check style-check flex items-center justify-between">
                                        <label class="form-check-label line-height-1 font-medium text-secondary-light"
                                            for="India">
                                            <span
                                                class="text-black hover-bXg-transparent hover-text-primary flex items-center gap-3">
                                                <img src="assets/images/flags/flag7.png" alt=""
                                                    class="w-9 h-9 bg-success-subtle text-success-600 rounded-full shrink-0">
                                                <span class="text-base font-semibold mb-0">India</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input rounded-full" name="language" type="radio"
                                            id="India">
                                    </div>

                                    <div class="form-check style-check flex items-center justify-between">
                                        <label class="form-check-label line-height-1 font-medium text-secondary-light"
                                            for="Koria">
                                            <span
                                                class="text-black hover-bXg-transparent hover-text-primary flex items-center gap-3">
                                                <img src="assets/images/flags/flag8.png" alt=""
                                                    class="w-9 h-9 bg-success-subtle text-success-600 rounded-full shrink-0">
                                                <span class="text-base font-semibold mb-0">Koria</span>
                                            </span>
                                        </label>
                                        <input class="form-check-input rounded-full" name="language" type="radio"
                                            id="Koria">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Language Dropdown End  -->


                    <!-- Message Dropdown Start  -->
                    <button data-dropdown-toggle="dropdownMessage"
                        class="has-indicator w-10 h-10 bg-neutral-200 dark:bg-neutral-700 rounded-full flex justify-center items-center"
                        type="button">
                        <iconify-icon icon="mage:email" class="text-neutral-900 dark:text-white text-xl"></iconify-icon>
                    </button>
                    <div id="dropdownMessage"
                        class="z-10 hidden bg-white dark:bg-neutral-700 rounded-2xl overflow-hidden shadow-lg max-w-[394px] w-full">
                        <div
                            class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 m-4 flex items-center justify-between gap-2">
                            <h6 class="text-lg text-neutral-900 font-semibold mb-0">Messaage</h6>
                            <span
                                class="w-10 h-10 bg-white dark:bg-neutral-600 text-primary-600 dark:text-white font-bold flex justify-center items-center rounded-full">05</span>
                        </div>
                        <div class="scroll-sm !border-t-0">
                            <div class="max-h-[400px] overflow-y-auto">
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 relative">
                                            <img class="rounded-full w-11 h-11"
                                                src="assets/images/notification/profile-3.png" alt="Joseph image">
                                            <span
                                                class="absolute end-[2px] bottom-[2px] w-2.5 h-2.5 bg-success-500 border border-white rounded-full dark:border-gray-600"></span>
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm line-clamp-1">hey! there i'm...</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex flex-col items-end gap-1">
                                        <span class="text-sm text-neutral-500">12:30 PM</span>
                                        <span
                                            class="w-4 h-4 text-xs bg-warning-600 text-white rounded-full flex justify-center items-center">8</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 relative">
                                            <img class="rounded-full w-11 h-11"
                                                src="assets/images/notification/profile-4.png" alt="Joseph image">
                                            <span
                                                class="absolute end-[2px] bottom-[2px] w-2.5 h-2.5 bg-success-500 border border-white rounded-full dark:border-gray-600"></span>
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm line-clamp-1">hey! there i'm...</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex flex-col items-end gap-1">
                                        <span class="text-sm text-neutral-500">12:30 PM</span>
                                        <span
                                            class="w-4 h-4 text-xs bg-warning-600 text-white rounded-full flex justify-center items-center">8</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 relative">
                                            <img class="rounded-full w-11 h-11"
                                                src="assets/images/notification/profile-5.png" alt="Joseph image">
                                            <span
                                                class="absolute end-[2px] bottom-[2px] w-2.5 h-2.5 bg-success-500 border border-white rounded-full dark:border-gray-600"></span>
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm line-clamp-1">hey! there i'm...</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex flex-col items-end gap-1">
                                        <span class="text-sm text-neutral-500">12:30 PM</span>
                                        <span
                                            class="w-4 h-4 text-xs bg-warning-600 text-white rounded-full flex justify-center items-center">8</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 relative">
                                            <img class="rounded-full w-11 h-11"
                                                src="assets/images/notification/profile-6.png" alt="Joseph image">
                                            <span
                                                class="absolute end-[2px] bottom-[2px] w-2.5 h-2.5 bg-success-500 border border-white rounded-full dark:border-gray-600"></span>
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm line-clamp-1">hey! there i'm...</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex flex-col items-end gap-1">
                                        <span class="text-sm text-neutral-500">12:30 PM</span>
                                        <span
                                            class="w-4 h-4 text-xs bg-warning-600 text-white rounded-full flex justify-center items-center">8</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 relative">
                                            <img class="rounded-full w-11 h-11"
                                                src="assets/images/notification/profile-7.png" alt="Joseph image">
                                            <span
                                                class="absolute end-[2px] bottom-[2px] w-2.5 h-2.5 bg-success-500 border border-white rounded-full dark:border-gray-600"></span>
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm line-clamp-1">hey! there i'm...</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex flex-col items-end gap-1">
                                        <span class="text-sm text-neutral-500">12:30 PM</span>
                                        <span
                                            class="w-4 h-4 text-xs bg-warning-600 text-white rounded-full flex justify-center items-center">8</span>
                                    </div>
                                </a>
                            </div>

                            <div class="text-center py-3 px-4">
                                <a href="javascript:void(0)"
                                    class="text-primary-600 dark:text-primary-600 font-semibold hover:underline text-center">See
                                    All Message </a>
                            </div>
                        </div>
                    </div>
                    <!-- Message Dropdown End  -->


                    <!-- Notification Start  -->
                    <button data-dropdown-toggle="dropdownNotification"
                        class="has-indicator w-10 h-10 bg-neutral-200 dark:bg-neutral-700 rounded-full flex justify-center items-center"
                        type="button">
                        <iconify-icon icon="iconoir:bell"
                            class="text-neutral-900 dark:text-white text-xl"></iconify-icon>
                    </button>
                    <div id="dropdownNotification"
                        class="z-10 hidden bg-white dark:bg-neutral-700 rounded-2xl overflow-hidden shadow-lg max-w-[394px] w-full">
                        <div
                            class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 m-4 flex items-center justify-between gap-2">
                            <h6 class="text-lg text-neutral-900 font-semibold mb-0">Notification</h6>
                            <span
                                class="w-10 h-10 bg-white dark:bg-neutral-600 text-primary-600 dark:text-white font-bold flex justify-center items-center rounded-full">05</span>
                        </div>
                        <div class="scroll-sm !border-t-0">
                            <div class="max-h-[400px] overflow-y-auto">
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex-shrink-0 relative w-11 h-11 bg-success-200 dark:bg-success-600/25 text-success-600 flex justify-center items-center rounded-full">
                                            <iconify-icon icon="bitcoin-icons:verify-outline"
                                                class="text-2xl"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Congratulations</h6>
                                            <p class="mb-0 text-sm line-clamp-1">Your profile has been Verified. Your
                                                profile has been Verified</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <span class="text-sm text-neutral-500">23 Mins ago</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 relative">
                                            <img class="rounded-full w-11 h-11"
                                                src="assets/images/notification/profile-4.png" alt="Joseph image">
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Ronald Richards</h6>
                                            <p class="mb-0 text-sm line-clamp-1">You can stitch between artboards</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <span class="text-sm text-neutral-500">23 Mins ago</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex-shrink-0 relative w-11 h-11 bg-primary-100 dark:bg-primary-600/25 text-primary-600 flex justify-center items-center rounded-full">
                                            AM
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Arlene McCoy</h6>
                                            <p class="mb-0 text-sm line-clamp-1">Invite you to prototyping</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <span class="text-sm text-neutral-500">23 Mins ago</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 relative">
                                            <img class="rounded-full w-11 h-11"
                                                src="assets/images/notification/profile-6.png" alt="Joseph image">
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Annette Black</h6>
                                            <p class="mb-0 text-sm line-clamp-1">Invite you to prototyping</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <span class="text-sm text-neutral-500">23 Mins ago</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex-shrink-0 relative w-11 h-11 bg-primary-100 dark:bg-primary-600/25 text-primary-600 flex justify-center items-center rounded-full">
                                            DR
                                        </div>
                                        <div>
                                            <h6 class="text-sm fw-semibold mb-1">Darlene Robertson</h6>
                                            <p class="mb-0 text-sm line-clamp-1">Invite you to prototyping</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <span class="text-sm text-neutral-500">23 Mins ago</span>
                                    </div>
                                </a>
                            </div>

                            <div class="text-center py-3 px-4">
                                <a href="javascript:void(0)"
                                    class="text-primary-600 dark:text-primary-600 font-semibold hover:underline text-center">See
                                    All Notification </a>
                            </div>
                        </div>
                    </div>
                    <!-- Notification End  -->


                    <button data-dropdown-toggle="dropdownProfile" class="flex justify-center items-center rounded-full"
                        type="button">
                        <img src="assets/images/user.png" alt="image" class="w-10 h-10 object-fit-cover rounded-full">
                    </button>
                    <div id="dropdownProfile"
                        class="z-10 hidden bg-white dark:bg-neutral-700 rounded-lg shadow-lg dropdown-menu-sm p-3">
                        <div
                            class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 mb-4 flex items-center justify-between gap-2">
                            <div>
                                <h6 class="text-lg text-neutral-900 font-semibold mb-0">Shahidul Islam</h6>
                                <span class="text-neutral-500">Admin</span>
                            </div>
                            <button type="button" class="hover:text-danger-600">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>

                        <div class="max-h-[400px] overflow-y-auto scroll-sm pe-2">
                            <ul class="flex flex-col">
                                <li>
                                    <a class="text-black px-0 py-2 hover:text-primary-600 flex items-center gap-4"
                                        href="view-profile.html">
                                        <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My
                                        Profile</a>
                                </li>
                                <li>
                                    <a class="text-black px-0 py-2 hover:text-primary-600 flex items-center gap-4"
                                        href="email.html">
                                        <iconify-icon icon="tabler:message-check" class="icon text-xl"></iconify-icon>
                                        Inbox</a>
                                </li>
                                <li>
                                    <a class="text-black px-0 py-2 hover:text-primary-600 flex items-center gap-4"
                                        href="company.html">
                                        <iconify-icon icon="icon-park-outline:setting-two"
                                            class="icon text-xl"></iconify-icon> Setting</a>
                                </li>
                                <li>
                                    <a class="text-black px-0 py-2 hover:text-danger-600 flex items-center gap-4"
                                        href="javascript:void(0)">
                                        <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log
                                        Out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="dashboard-main-body">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h6 class="font-semibold mb-0 dark:text-white">Dashboard</h6>
            <ul class="flex items-center gap-[6px]">
                <li class="font-medium">
                    <a href="index.html"
                        class="flex items-center gap-2 text-neutral-600 hover:text-primary-600 dark:text-white dark:hover:text-primary-600">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li class="text-neutral-600 dark:text-white">-</li>
                <li class="text-neutral-600 font-medium dark:text-white">AI</li>
            </ul>
        </div>

        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 3xl:grid-cols-5 gap-6">
            <div
                class="card shadow-none border border-gray-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg h-full bg-gradient-to-r from-cyan-600/10 to-bg-white">
                <div class="card-body p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white mb-1">Total Users</p>
                            <h6 class="mb-0 dark:text-white">20,000</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-cyan-600 rounded-full flex justify-center items-center">
                            <iconify-icon icon="gridicons:multiple-users"
                                class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="font-medium text-sm text-neutral-600 dark:text-white mt-3 mb-0 flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1 text-success-600 dark:text-success-400"><iconify-icon
                                icon="bxs:up-arrow" class="text-xs"></iconify-icon> +4000</span>
                        Last 30 days users
                    </p>
                </div>
            </div><!-- card end -->
            <div
                class="card shadow-none border border-gray-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg h-full bg-gradient-to-r from-purple-600/10 to-bg-white">
                <div class="card-body p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white mb-1">Total Subscription</p>
                            <h6 class="mb-0 dark:text-white">15,000</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-purple-600 rounded-full flex justify-center items-center">
                            <iconify-icon icon="fa-solid:award" class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="font-medium text-sm text-neutral-600 dark:text-white mt-3 mb-0 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 text-danger-600 dark:text-danger-400"><iconify-icon
                                icon="bxs:down-arrow" class="text-xs"></iconify-icon> -800</span>
                        Last 30 days subscription
                    </p>
                </div>
            </div><!-- card end -->
            <div
                class="card shadow-none border border-gray-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg h-full bg-gradient-to-r from-blue-600/10 to-bg-white">
                <div class="card-body p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white mb-1">Total Free Users</p>
                            <h6 class="mb-0 dark:text-white">5,000</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-blue-600 rounded-full flex justify-center items-center">
                            <iconify-icon icon="fluent:people-20-filled"
                                class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="font-medium text-sm text-neutral-600 dark:text-white mt-3 mb-0 flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1 text-success-600 dark:text-success-400"><iconify-icon
                                icon="bxs:up-arrow" class="text-xs"></iconify-icon> +200</span>
                        Last 30 days users
                    </p>
                </div>
            </div><!-- card end -->
            <div
                class="card shadow-none border border-gray-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg h-full bg-gradient-to-r from-success-600/10 to-bg-white">
                <div class="card-body p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white mb-1">Total Income</p>
                            <h6 class="mb-0 dark:text-white">$42,000</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-success-600 rounded-full flex justify-center items-center">
                            <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="font-medium text-sm text-neutral-600 dark:text-white mt-3 mb-0 flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1 text-success-600 dark:text-success-400"><iconify-icon
                                icon="bxs:up-arrow" class="text-xs"></iconify-icon> +$20,000</span>
                        Last 30 days income
                    </p>
                </div>
            </div><!-- card end -->
            <div
                class="card shadow-none border border-gray-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg h-full bg-gradient-to-r from-red-600/10 to-bg-white">
                <div class="card-body p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white mb-1">Total Expense</p>
                            <h6 class="mb-0 dark:text-white">$30,000</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-red-600 rounded-full flex justify-center items-center">
                            <iconify-icon icon="fa6-solid:file-invoice-dollar"
                                class="text-white text-2xl mb-0"></iconify-icon>
                        </div>
                    </div>
                    <p class="font-medium text-sm text-neutral-600 dark:text-white mt-3 mb-0 flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1 text-success-600 dark:text-success-400"><iconify-icon
                                icon="bxs:up-arrow" class="text-xs"></iconify-icon> +$5,000</span>
                        Last 30 days expense
                    </p>
                </div>
            </div><!-- card end -->
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6">
            <div class="xl:col-span-12 2xl:col-span-6">
                <div class="card h-full rounded-lg border-0">
                    <div class="card-body">
                        <div class="flex flex-wrap items-center justify-between">
                            <h6 class="text-lg mb-0">Sales Statistic</h6>
                            <select class="form-select bg-white dark:bg-neutral-700 form-select-sm w-auto">
                                <option>Yearly</option>
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Today</option>
                            </select>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <h6 class="mb-0">$27,200</h6>
                            <span
                                class="text-sm font-semibold rounded-full bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 border border-success-200 dark:border-success-600/50 px-2 py-1.5 line-height-1 flex items-center gap-1">
                                10% <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                            </span>
                            <span class="text-xs font-medium">+ $1400 Per Day</span>
                        </div>
                        <div id="chart" class="pt-[28px] apexcharts-tooltip-style-1"></div>
                    </div>
                </div>
            </div>
            <div class="xl:col-span-6 2xl:col-span-3">
                <div class="card h-full rounded-lg border-0">
                    <div class="card-body p-6">
                        <h6 class="mb-3 font-semibold text-lg">Total Subscriber</h6>
                        <div class="flex items-center gap-2 mb-5">
                            <h6 class="font-semibold mb-0">5,000</h6>
                            <span
                                class="text-sm font-semibold rounded-full bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 border border-danger-200 dark:border-danger-600/50 px-2 py-1.5 line-height-1 flex items-center gap-1">
                                10% <iconify-icon icon="iconamoon:arrow-down-2-fill" class="icon"></iconify-icon>
                            </span>
                            - 20 Per Day
                        </div>

                        <div id="barChart"></div>

                    </div>
                </div>
            </div>
            <div class="xl:col-span-6 2xl:col-span-3">
                <div class="card h-full rounded-lg border-0 overflow-hidden">
                    <div class="card-body p-6">
                        <div class="flex items-center flex-wrap gap-2 justify-between">
                            <h6 class="mb-2 font-bold text-lg">Users Overview</h6>
                            <div class="">
                                <select
                                    class="form-select form-select-sm w-auto bg-white dark:bg-neutral-700 border text-secondary-light">
                                    <option>Today</option>
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                    <option>Yearly</option>
                                </select>
                            </div>
                        </div>


                        <div id="userOverviewDonutChart" class="apexcharts-tooltip-z-none"></div>

                        <ul class="flex flex-wrap items-center justify-between mt-4 gap-3">
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-sm bg-primary-600"></span>
                                <span class="text-secondary-light text-sm font-normal">New:
                                    <span class="text-neutral-600 dark:text-neutral-200 font-semibold">400</span>
                                </span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-sm bg-warning-600"></span>
                                <span class="text-secondary-light text-sm font-normal">Subscribed:
                                    <span class="text-neutral-600 dark:text-neutral-200 font-semibold">300</span>
                                </span>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="xl:col-span-12 2xl:col-span-9">
                <div class="card h-full border-0">
                    <div class="card-body p-6">

                        <div class="mb-4">
                            <ul class="tab-style-gradient flex flex-wrap -mb-px text-sm font-medium text-center"
                                id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                                <li class="" role="presentation">
                                    <button
                                        class="py-2.5 px-4 border-t-2 font-semibold text-lg inline-flex items-center gap-3 text-neutral-600"
                                        id="registered-tab" data-tabs-target="#registered" type="button" role="tab"
                                        aria-controls="registered" aria-selected="false">
                                        Latest Registered
                                        <span
                                            class="text-white px-2 py-0.5 bg-neutral-600 rounded-full text-sm">20</span>
                                    </button>
                                </li>
                                <li class="" role="presentation">
                                    <button
                                        class="py-2.5 px-4 border-t-2 font-semibold text-lg inline-flex items-center gap-3 text-neutral-600 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                        id="subscribe-tab" data-tabs-target="#subscribe" type="button" role="tab"
                                        aria-controls="subscribe" aria-selected="false">
                                        Latest Subscribe
                                        <span
                                            class="text-white px-2 py-0.5 bg-neutral-600 rounded-full text-sm">20</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div id="default-tab-content">
                            <div class="hidden" id="registered" role="tabpanel" aria-labelledby="registered-tab">
                                <div class="overflow-x-auto">
                                    <table class="table bordered-table sm-table mb-0 table-auto">
                                        <thead>
                                            <tr>
                                                <th scope="col">Users </th>
                                                <th scope="col">Registered On</th>
                                                <th scope="col">Plan</th>
                                                <th scope="col" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user1.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Dianne Russell</h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">redaniel@gmail.com</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Free</td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user2.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Wade Warren</h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">xterris@gmail.com</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Basic</td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user3.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Albert Flores</h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">seannand@mail.ru</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Standard</td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user4.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Bessie Cooper </h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">igerrin@gmail.com</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Business</td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user5.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Arlene McCoy</h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">fellora@mail.ru</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Enterprise </td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="hidden" id="subscribe" role="tabpanel" aria-labelledby="subscribe-tab">
                                <div class="overflow-x-auto">
                                    <table class="table bordered-table sm-table mb-0 table-auto">
                                        <thead>
                                            <tr>
                                                <th scope="col">Users Name </th>
                                                <th scope="col">Registered On</th>
                                                <th scope="col">Plan</th>
                                                <th scope="col" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user1.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Dianne Russell</h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">redaniel@gmail.com</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Free</td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user2.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Wade Warren</h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">xterris@gmail.com</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Basic</td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user3.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Albert Flores</h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">seannand@mail.ru</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Standard</td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user4.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Bessie Cooper </h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">igerrin@gmail.com</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Business</td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="assets/images/users/user5.png" alt=""
                                                            class="w-10 h-10 rounded-full shrink-0 me-2 overflow-hidden">
                                                        <div class="grow">
                                                            <h6 class="text-base mb-0 font-medium">Arlene McCoy</h6>
                                                            <span
                                                                class="text-sm text-secondary-light font-medium">fellora@mail.ru</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>27 Mar 2024</td>
                                                <td>Enterprise </td>
                                                <td class="text-center">
                                                    <span
                                                        class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">Active</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="xl:col-span-6 2xl:col-span-3">
                <div class="card h-full border-0">
                    <div class="card-body">
                        <div class="flex items-center flex-wrap gap-2 justify-between">
                            <h6 class="font-bold text-lg mb-0">Top Performer</h6>
                            <a href="javascript:void(0)"
                                class="text-primary-600 dark:text-primary-600 hover-text-primary flex items-center gap-1">
                                View All
                                <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                            </a>
                        </div>

                        <div class="mt-8">

                            <div class="flex items-center justify-between gap-2 mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="assets/images/users/user1.png" alt=""
                                        class="w-10 h-10 rounded-full shrink-0 overflow-hidden">
                                    <div class="grow">
                                        <h6 class="text-base mb-0 font-medium">Dianne Russell</h6>
                                        <span class="text-sm text-secondary-light font-medium">Agent ID: 36254</span>
                                    </div>
                                </div>
                                <span class="text-neutral-600 dark:text-neutral-200 text-base font-medium">$20</span>
                            </div>

                            <div class="flex items-center justify-between gap-2 mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="assets/images/users/user2.png" alt=""
                                        class="w-10 h-10 rounded-full shrink-0 overflow-hidden">
                                    <div class="grow">
                                        <h6 class="text-base mb-0 font-medium">Wade Warren</h6>
                                        <span class="text-sm text-secondary-light font-medium">Agent ID: 36254</span>
                                    </div>
                                </div>
                                <span class="text-neutral-600 dark:text-neutral-200 text-base font-medium">$20</span>
                            </div>

                            <div class="flex items-center justify-between gap-2 mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="assets/images/users/user3.png" alt=""
                                        class="w-10 h-10 rounded-full shrink-0 overflow-hidden">
                                    <div class="grow">
                                        <h6 class="text-base mb-0 font-medium">Albert Flores</h6>
                                        <span class="text-sm text-secondary-light font-medium">Agent ID: 36254</span>
                                    </div>
                                </div>
                                <span class="text-neutral-600 dark:text-neutral-200 text-base font-medium">$30</span>
                            </div>

                            <div class="flex items-center justify-between gap-2 mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="assets/images/users/user4.png" alt=""
                                        class="w-10 h-10 rounded-full shrink-0 overflow-hidden">
                                    <div class="grow">
                                        <h6 class="text-base mb-0 font-medium">Bessie Cooper</h6>
                                        <span class="text-sm text-secondary-light font-medium">Agent ID: 36254</span>
                                    </div>
                                </div>
                                <span class="text-neutral-600 dark:text-neutral-200 text-base font-medium">$40</span>
                            </div>

                            <div class="flex items-center justify-between gap-2 mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="assets/images/users/user5.png" alt=""
                                        class="w-10 h-10 rounded-full shrink-0 overflow-hidden">
                                    <div class="grow">
                                        <h6 class="text-base mb-0 font-medium">Arlene McCoy</h6>
                                        <span class="text-sm text-secondary-light font-medium">Agent ID: 36254</span>
                                    </div>
                                </div>
                                <span class="text-neutral-600 dark:text-neutral-200 text-base font-medium">$10</span>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <img src="assets/images/users/user1.png" alt=""
                                        class="w-10 h-10 rounded-full shrink-0 overflow-hidden">
                                    <div class="grow">
                                        <h6 class="text-base mb-0 font-medium">Arlene McCoy</h6>
                                        <span class="text-sm text-secondary-light font-medium">Agent ID: 36254</span>
                                    </div>
                                </div>
                                <span class="text-neutral-600 dark:text-neutral-200 text-base font-medium">$10</span>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="xl:col-span-6 2xl:col-span-6">
                <div class="card h-full border-0">
                    <div class="card-body">
                        <div class="flex items-center flex-wrap gap-2 justify-between mb-5">
                            <h6 class="font-bold text-lg mb-0">Top Countries</h6>
                            <select
                                class="form-select form-select-sm w-auto bg-white dark:bg-neutral-700 border text-secondary-light">
                                <option>Today</option>
                                <option>Weekly</option>
                                <option>Monthly</option>
                                <option>Yearly</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div id="world-map"
                                class="h-full border border-neutral-200 dark:border-neutral-600 rounded-lg"></div>
                            <div class="h-full border border-neutral-200 dark:border-neutral-600 p-4 pe-0 rounded-lg">
                                <div class="max-h-[266px] overflow-y-auto scroll-sm pe-6">
                                    <div class="flex items-center justify-between gap-3 mb-3 pb-2">
                                        <div class="flex items-center">
                                            <img src="assets/images/flags/flag1.png" alt=""
                                                class="w-10 h-10 rounded-full shrink-0 me-4">
                                            <div class="grow">
                                                <h6 class="text-sm mb-0">USA</h6>
                                                <span class="text-xs text-secondary-light font-medium">1,240
                                                    Users</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-full max-w-66 ms-auto">
                                                <div class="progress progress-sm rounded-full" role="progressbar"
                                                    aria-label="Success example" aria-valuenow="25" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <div class="progress-bar bg-primary-600 rounded-full"
                                                        style="width: 80%;"></div>
                                                </div>
                                            </div>
                                            <span class="text-secondary-light font-xs font-semibold">80%</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mb-3 pb-2">
                                        <div class="flex items-center">
                                            <img src="assets/images/flags/flag2.png" alt=""
                                                class="w-10 h-10 rounded-full shrink-0 me-4">
                                            <div class="grow">
                                                <h6 class="text-sm mb-0">Japan</h6>
                                                <span class="text-xs text-secondary-light font-medium">1,240
                                                    Users</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-full max-w-66 ms-auto">
                                                <div class="progress progress-sm rounded-full" role="progressbar"
                                                    aria-label="Success example" aria-valuenow="25" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <div class="progress-bar bg-orange rounded-full"
                                                        style="width: 60%;"></div>
                                                </div>
                                            </div>
                                            <span class="text-secondary-light font-xs font-semibold">60%</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mb-3 pb-2">
                                        <div class="flex items-center">
                                            <img src="assets/images/flags/flag3.png" alt=""
                                                class="w-10 h-10 rounded-full shrink-0 me-4">
                                            <div class="grow">
                                                <h6 class="text-sm mb-0">France</h6>
                                                <span class="text-xs text-secondary-light font-medium">1,240
                                                    Users</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-full max-w-66 ms-auto">
                                                <div class="progress progress-sm rounded-full" role="progressbar"
                                                    aria-label="Success example" aria-valuenow="25" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <div class="progress-bar bg-warning-600 rounded-full"
                                                        style="width: 49%;"></div>
                                                </div>
                                            </div>
                                            <span class="text-secondary-light font-xs font-semibold">49%</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mb-3 pb-2">
                                        <div class="flex items-center">
                                            <img src="assets/images/flags/flag4.png" alt=""
                                                class="w-10 h-10 rounded-full shrink-0 me-4">
                                            <div class="grow">
                                                <h6 class="text-sm mb-0">Germany</h6>
                                                <span class="text-xs text-secondary-light font-medium">1,240
                                                    Users</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-full max-w-66 ms-auto">
                                                <div class="progress progress-sm rounded-full" role="progressbar"
                                                    aria-label="Success example" aria-valuenow="25" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <div class="progress-bar bg-success-600 rounded-full"
                                                        style="width: 100%;"></div>
                                                </div>
                                            </div>
                                            <span class="text-secondary-light font-xs font-semibold">100%</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mb-3 pb-2">
                                        <div class="flex items-center">
                                            <img src="assets/images/flags/flag5.png" alt=""
                                                class="w-10 h-10 rounded-full shrink-0 me-4">
                                            <div class="grow">
                                                <h6 class="text-sm mb-0">South Korea</h6>
                                                <span class="text-xs text-secondary-light font-medium">1,240
                                                    Users</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-full max-w-66 ms-auto">
                                                <div class="progress progress-sm rounded-full" role="progressbar"
                                                    aria-label="Success example" aria-valuenow="25" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <div class="progress-bar bg-info-600 rounded-full"
                                                        style="width: 30%;"></div>
                                                </div>
                                            </div>
                                            <span class="text-secondary-light font-xs font-semibold">30%</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center">
                                            <img src="assets/images/flags/flag1.png" alt=""
                                                class="w-10 h-10 rounded-full shrink-0 me-4">
                                            <div class="grow">
                                                <h6 class="text-sm mb-0">USA</h6>
                                                <span class="text-xs text-secondary-light font-medium">1,240
                                                    Users</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-full max-w-66 ms-auto">
                                                <div class="progress progress-sm rounded-full" role="progressbar"
                                                    aria-label="Success example" aria-valuenow="25" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <div class="progress-bar bg-primary-600 rounded-full"
                                                        style="width: 80%;"></div>
                                                </div>
                                            </div>
                                            <span class="text-secondary-light font-xs font-semibold">80%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="xl:col-span-6 2xl:col-span-6">
                <div class="card h-full border-0">
                    <div class="card-body">
                        <div class="flex items-center flex-wrap gap-2 justify-between">
                            <h6 class="font-bold text-lg mb-0">Generated Content</h6>
                            <select
                                class="form-select form-select-sm w-auto bg-white dark:bg-neutral-700 border text-secondary-light">
                                <option>Today</option>
                                <option>Weekly</option>
                                <option>Monthly</option>
                                <option>Yearly</option>
                            </select>
                        </div>

                        <ul class="flex flex-wrap items-center mt-4 gap-3">
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-primary-600"></span>
                                <span class="text-secondary-light text-sm font-semibold">Word:
                                    <span class="text-neutral-600 dark:text-neutral-200 font-bold">400</span>
                                </span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-warning-600"></span>
                                <span class="text-secondary-light text-sm font-semibold">Image:
                                    <span class="text-neutral-600 dark:text-neutral-200 font-bold">300</span>
                                </span>
                            </li>
                        </ul>

                        <div class="mt-[60px]">
                            <div id="paymentStatusChart" class="margin-16-minus"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <footer class="d-footer">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <p class="mb-0 text-neutral-600">&copy; 2024 WowDash. All Rights Reserved.</p>
            <p class="mb-0">Made by <a href="https://themeforest.net/user/wowtheme7/portfolio"
                    class="text-primary-600 dark:text-primary-600 hover:underline">wowtheme7</a></p>
        </div>
    </footer>
</main>