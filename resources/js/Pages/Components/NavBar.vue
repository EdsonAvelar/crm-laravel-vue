<template>
    <nav class="bg-gray-900 text-white shadow-md ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <img class="h-8 w-8" src="https://placehold.co/40x40" alt="Cork Logo">
                </div>

                <!-- Search Bar -->
                <div class="flex-grow px-4 max-w-xs relative">
                    <span class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i> <!-- Ícone da lupa -->
                    </span>
                    <input
                        class="w-full py-1 pl-9 pr-2 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring focus:border-blue-500"
                        type="text" placeholder="Search..." />
                </div>

                <!-- Menu on the Right -->
                <div class="ml-auto flex items-center space-x-6">
                    <!-- Icon 1: System -->
                    <a href="#" class="hover:text-gray-300">
                        <i class="fas fa-desktop"></i> <!-- Use FontAwesome or any icon library -->
                    </a>

                    <!-- Icon 2: Notifications -->
                    <a href="#" class="hover:text-gray-300">
                        <i class="fas fa-bell"></i>
                    </a>

                    <!-- Icon 3: Language Selector -->
                    <a href="#" class="hover:text-gray-300">
                        <i class="fas fa-globe"></i>
                    </a>

                    <!-- User Profile Dropdown -->
                    <div class="relative">
                        <button @click="toggleDropdown" ref="dropdownButton"
                            class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                            id="user-menu" aria-expanded="false" aria-haspopup="true">
                            <img class="h-8 w-8 rounded-full" src="https://placehold.co/40x40" alt="User Avatar">
                        </button>

                        <!-- Dropdown Menu with Transition -->
                        <transition enter-active-class="transition ease-out duration-200"
                            enter-class="opacity-0 transform scale-95" enter-to-class="opacity-100 transform scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-class="opacity-100 transform scale-100" leave-to-class="opacity-0 transform scale-95">
                            <div v-if="isDropdownOpen" ref="dropdownMenu"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Settings</a>
                                <Link :href="route('logout')" method="post" type="button"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign
                                out</Link>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            isDropdownOpen: false,
        };
    },
    methods: {
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        closeDropdown() {
            this.isDropdownOpen = false;
        },
        handleClickOutside(event) {
            // Verifica se o clique foi fora do dropdown e do botão
            if (
                this.isDropdownOpen &&
                this.$refs.dropdownMenu &&
                !this.$refs.dropdownMenu.contains(event.target) &&
                !this.$refs.dropdownButton.contains(event.target)
            ) {
                this.closeDropdown();
            }
        },
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    },
};
</script>

<style scoped>
/* Adicional de estilização, caso necessário */
</style>
