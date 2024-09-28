<script setup>
import NavBar from '../Pages/Components/NavBar.vue'
import SideBar from '../Pages/Components/SideBar.vue'
import { ref } from 'vue'

const isSidebarExpanded = ref(false)

function toggleSidebar() {
    isSidebarExpanded.value = !isSidebarExpanded.value
}
</script>

<template>
    <!-- Navbar fixo no topo -->
    <header class="w-full sticky top-0 z-50">
        <NavBar @toggle-sidebar="toggleSidebar" />
    </header>

    <!-- Estrutura flexível para Sidebar e Conteúdo Principal -->
    <div class="flex">
        <!-- Sidebar abaixo do Navbar -->
        <SideBar :is-expanded="isSidebarExpanded" />

        <!-- Conteúdo Principal -->
        <div :class="[isSidebarExpanded ? 'ml-64' : 'ml-16', 'transition-all duration-300 flex-1 p-6']">
            <slot />
        </div>
    </div>
</template>
