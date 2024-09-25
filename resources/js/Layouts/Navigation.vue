<template>
    <div
        :class="$page.props.showingMobileMenu ? 'block' : 'hidden'"
        @click="$page.props.showingMobileMenu = false"
        class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"
    ></div>

    <div
        :class="
            $page.props.showingMobileMenu
                ? 'translate-x-0 ease-out'
                : '-translate-x-full ease-in'
        "
        class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-pink-900 lg:translate-x-0 lg:static lg:inset-0"
    >
        <div class="flex items-center justify-center mt-8">
            <div class="flex items-center">
                <!-- <img :src="$page.props.auth.user.app_logo??'itimages/it.png'" alt="" srcset="" style="height: 70px;">

                <span class="mx-2 text-2xl font-semibold text-white"
                    >{{ $page.props.auth.user.app_name??'Innovation Trove' }}</span
                > -->
            </div>
        </div>

        <nav
            v-if="$page.props.auth.user.roles.includes('Seller')"
            class="mt-10"
            x-data="{ isMultiLevelMenuOpen: false }"
        >
            <nav-link
                :href="route('dashboard')"
                :active="route().current('dashboard')"
            >
                <template #icon>
                    <svg
                        class="w-6 h-6"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
                        />
                    </svg>
                </template>
                Dashboard
            </nav-link>

            <!-- <nav-link
                :href="route('users.index')"
                :active="route().current('users.index')"
            >
                <template #icon>
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                        ></path>
                    </svg>
                </template>
                Users
            </nav-link> -->

            <nav-link :href="route('subscription.index')" :active="route().current('subscription.index')">
                <template #icon>
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                        ></path>
                    </svg>
                </template>
                Subscription
            </nav-link>

            <a
                class="flex items-center px-6 py-2 mt-4 text-gray-100 no-underline dropdown-toggle"
                href="#"
                @click="showingTwoLevelMenu = !showingTwoLevelMenu"
            >
                <svg
                    class="w-6 h-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"
                    ></path>
                </svg>
                <span class="mx-3">Firm</span>
            </a>
            <transition
                enter-to-class="transition-all duration-300 ease-in-out"
                enter-from-class="opacity-25 max-h-0"
                leave-from-class="opacity-100 max-h-xl"
                leave-to-class="opacity-0 max-h-0"
            >
                <div v-show="showingTwoLevelMenu">
                    <ul
                        class="p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"
                        aria-label="submenu"
                    >
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link
                                class="text-white no-underline first-letter:w-full"
                                :href="route('company.index')"
                                >Create Firm</Link
                            >
                        </li>
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link
                                class="text-white no-underline first-letter:w-full"
                                :href="route('company.view')"
                                >View Firm</Link
                            >
                        </li>
                    </ul>
                </div>
            </transition>
            <a
                class="flex items-center px-6 py-2 mt-4 text-gray-100 no-underline dropdown-toggle"
                href="#"
                @click="showingCustomerMenu = !showingCustomerMenu"
            >
                <svg
                    class="w-6 h-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"
                    ></path>
                </svg>
                <span class="mx-3">Customers</span>
            </a>
            <transition
                enter-to-class="transition-all duration-300 ease-in-out"
                enter-from-class="opacity-25 max-h-0"
                leave-from-class="opacity-100 max-h-xl"
                leave-to-class="opacity-0 max-h-0"
            >
                <div v-show="showingCustomerMenu">
                    <ul
                        class="p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"
                        aria-label="submenu"
                    >
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link
                                class="text-white no-underline first-letter:w-full"
                                :href="route('company.customer')"
                                >Create Customer</Link
                            >
                        </li>
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link
                                class="text-white no-underline first-letter:w-full"
                                :href="route('company.customer.view')"
                                >View Customer</Link
                            >
                        </li>
                    </ul>
                </div>
            </transition>
            <a
                class="flex items-center px-6 py-2 mt-4 text-gray-100 no-underline dropdown-toggle"
                href="#"
                @click="showingInvoices = !showingInvoices"
            >
                <svg
                    class="w-6 h-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"
                    ></path>
                </svg>
                <span class="mx-3">Invoices</span>
            </a>
            <transition
                enter-to-class="transition-all duration-300 ease-in-out"
                enter-from-class="opacity-25 max-h-0"
                leave-from-class="opacity-100 max-h-xl"
                leave-to-class="opacity-0 max-h-0"
            >
                <div v-show="showingInvoices">
                    <ul
                        class="p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"
                        aria-label="submenu"
                    >
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link
                                class="text-white no-underline first-letter:w-full"
                                :href="route('customer.bill')"
                                >Create Invoice</Link
                            >
                        </li>
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link
                                class="text-white no-underline first-letter:w-full"
                                :href="route('invoice.list')"
                                >View Invoice List</Link
                            >
                        </li>
                    </ul>
                </div>
            </transition>

            <nav-link :href="route('payment-request.index')" :active="route().current('payment-request.index')">
                <template #icon>
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                        ></path>
                    </svg>
                </template>
                Payment Requests
            </nav-link>

        </nav>
        <nav v-else class="mt-10" x-data="{ isMultiLevelMenuOpen: false }">
            <nav-link
                :href="route('dashboard')"
                :active="route().current('dashboard')"
            >
                <template #icon>
                    <svg
                        class="w-6 h-6"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
                        />
                    </svg>
                </template>
                Dashboard
            </nav-link>

            <!-- <nav-link
                href="#"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
            >
                <template #icon>
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                        ></path>
                    </svg>
                </template>
                <span class="d-flex"
                    >Users &nbsp;&nbsp;<img
                        src="itimages/icon.png"
                        style="height: 20px"
                        alt="Example Image"
                /></span>
            </nav-link> -->

            <nav-link
                href="#"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
            >
                <template #icon>
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                        ></path>
                    </svg>
                </template>
                <span class="d-flex"
                    >Subscription &nbsp;&nbsp;<img
                        src="itimages/icon.png"
                        style="height: 20px"
                        alt="Example Image"
                /></span>
            </nav-link>

            <a
                class="flex items-center px-6 py-2 mt-4 text-gray-100 no-underline"
                href="#"
                @click="showingTwoLevelMenu = !showingTwoLevelMenu"
            >
                <svg
                    class="w-6 h-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"
                    ></path>
                </svg>
                <span class="mx-3 d-flex">Companies &nbsp;&nbsp;<img
                        src="itimages/icon.png"
                        style="height: 20px"
                        alt="Example Image"
                /></span>
            </a>
            <transition
                enter-to-class="transition-all duration-300 ease-in-out"
                enter-from-class="opacity-25 max-h-0"
                leave-from-class="opacity-100 max-h-xl"
                leave-to-class="opacity-0 max-h-0"
            >
                <div v-show="showingTwoLevelMenu">
                    <ul
                        class="p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"
                        aria-label="submenu"
                    >
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link
                                class="text-white no-underline first-letter:w-full"
                                href="#"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                                >Create Company</Link
                            >
                        </li>
                    </ul>
                </div>
            </transition>
            <a
                class="flex items-center px-6 py-2 mt-4 text-gray-100 no-underline"
                href="#"
                @click="showingTwoLevelMenu2 = !showingTwoLevelMenu2"
            >
                <svg
                    class="w-6 h-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"
                    ></path>
                </svg>
                <span class="mx-3 d-flex">Companies &nbsp;&nbsp;<img
                        src="itimages/icon.png"
                        style="height: 20px"
                        alt="Example Image"
                /></span>
            </a>
            <transition
                enter-to-class="transition-all duration-300 ease-in-out"
                enter-from-class="opacity-25 max-h-0"
                leave-from-class="opacity-100 max-h-xl"
                leave-to-class="opacity-0 max-h-0"
            >
                <div v-show="showingTwoLevelMenu2">
                    <ul
                        class="p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"
                        aria-label="submenu"
                    >
                        <li class="px-2 py-1 transition-colors duration-150">
                            <Link
                                class="text-white no-underline first-letter:w-full"
                                href="#"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                                >Create Company</Link
                            >
                        </li>
                    </ul>
                </div>
            </transition>
        </nav>
    </div>
    <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    You don't have any Subscription Plans yet. Please click on Subscription Plans button to continue !
                </div>
                <div class="modal-footer">
                    <Link
                        href="/subscription"
                        class="btn btn-primary"
                        data-bs-dismiss="modal"
                        as="button"
                        type="button"
                        >Subscription Plans</Link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import NavLink from "@/Components/NavLink.vue";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

export default {
    components: {
        NavLink,
        Link,
    },

    setup() {
        let showingTwoLevelMenu = ref(false);
        let showingTwoLevelMenu2 = ref(false);
        let showingCustomerMenu = ref(false);
        let showingInvoices = ref(false);

        return {
            showingTwoLevelMenu,
            showingTwoLevelMenu2,
            showingCustomerMenu,
            showingInvoices,
        };
    },

};
</script>

<style scoped></style>
