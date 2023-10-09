<template>
    <div>
        <Transition name="bounce">
            <div
                v-if="$page.props.flash.success && show"
                class="flex z-50 position-absolute right-10 top-10 items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                role="alert"
            >
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200"
                >
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                        />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ml-3 text-sm font-normal">
                    {{ $page.props.flash.success }}
                </div>
                <button
                    type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    @click="show = false"
                >
                    <svg
                        class="block w-2 h-2 fill-green-800 group-hover:fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
        </Transition>
        <Transition name="bounce">
            <div
                v-if="
                    ($page.props.flash.error ||
                        Object.keys($page.props.errors).length > 0) &&
                    show
                "
                class="flex z-50 position-absolute right-10 top-5 items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                role="alert"
            >
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200"
                >
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"
                        />
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
                <div
                    v-if="$page.props.flash.error"
                    class="ml-3 text-sm font-normal"
                >
                    {{ $page.props.flash.error }}
                </div>
                <div v-else class="ml-3 text-sm font-normal">
                    <span v-if="Object.keys($page.props.errors).length === 1"
                        >There is one form error.</span
                    >
                    <span v-else
                        >There are
                        {{ Object.keys($page.props.errors).length }} form
                        errors.</span
                    >
                </div>

                <button
                    type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    @click="show = false"
                >
                    <svg
                        class="block w-2 h-2 fill-red-800 group-hover:fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
        </Transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            show: true,
        };
    },
    watch: {
        "$page.props.flash": {
            handler() {
                this.show = true;
            },
            deep: true,
        },
    },
};
</script>

<style scoped>
.bounce-enter-active {
  animation: bounce-in 0.5s;
}
.bounce-leave-active {
  animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.25);
  }
  100% {
    transform: scale(1);
  }
}
</style>
