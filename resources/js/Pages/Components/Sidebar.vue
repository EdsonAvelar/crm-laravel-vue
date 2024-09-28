<template>
    <div class="flex ">
        <!-- Sidebar -->
        <div :class="[
            'fixed top-0 left-0 h-full bg-gray-900 text-white transition-all duration-500 ease-in-out z-50 ml-4 top-20  rounded-md',
            sidebarExpanded || isHovered ? 'w-64 ' : 'w-16 ', isHovered ? 'red-drop-shadow' : ''
        ]" @mouseenter="hoverSidebar(true)" @mouseleave="hoverSidebar(false)">
            <!-- Botão de Hambúrguer -->
            <div class="p-4 flex justify-between items-center border-b border-gray-700">
                <span class="text-xl font-bold" v-if="sidebarExpanded || isHovered">CORK</span>
                <button @click="toggleSidebar" class="focus:outline-none">
                    <i :class="[sidebarExpanded ? 'fas fa-times' : 'fas fa-bars']"></i>
                </button>
            </div>

            <!-- Ícones e Itens do Menu -->
            <div class="mt-4 space-y-3 p-1">
                <div v-for="(item, index) in menuItems" :key="index" class="group">
                    <Link :href="!item.subItems ? route(item.route) : '/home'" type="button"
                        :method="item.method ? item.method : 'get'" @click="toggleSubmenu(index)"
                        class="flex items-center justify-between space-x-4 p-4 hover:bg-gray-800 rounded-md transition duration-200">

                    <div class="flex items-center space-x-4">
                        <i :class="[item.icon, 'text-xl']"></i>
                        <span :class="[
                            'transition-all duration-300',
                            sidebarExpanded || isHovered ? 'opacity-100 visible' : 'opacity-0 invisible'
                        ]" class="whitespace-nowrap">

                            <span v-if="(sidebarExpanded || isHovered)">

                                {{ item.label }}
                            </span>

                        </span>

                    </div>
                    <!-- Seta para indicar submenu -->
                    <span v-if="(sidebarExpanded || isHovered)">
                        <i v-if="item.subItems" :class="[item.isOpen ? 'fas fa-chevron-down' : 'fas fa-chevron-right']"
                            class="ml-auto"></i>
                    </span>
                    </Link>


                    <!-- Subitens (se houver) -->
                    <div :class="[
                        'transition-all duration-300',
                        sidebarExpanded || isHovered ? 'opacity-100 visible' : 'opacity-0 invisible'
                    ]">
                        <div v-if="item.isOpen && item.subItems && (sidebarExpanded || isHovered)" class="ml-8">
                            <div v-for="(subItem, subIndex) in item.subItems" :key="subIndex" class="p-2">
                                <Link :href="route(subItem.route)" type="button"
                                    class="text-gray-400 hover:text-gray-200">{{ subItem.label }}</Link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div :class="[
            'p-2 flex-grow transition-all duration-300',
            sidebarExpanded ? 'ml-48' : 'ml-1'
        ]">
            <slot></slot>
        </div>
    </div>
</template>

<script>




export default {
    data() {
        return {
            sidebarExpanded: false,
            isHovered: false,
            item_opened: null,
            menuItems: [
                {
                    icon: 'fas fa-home',
                    label: 'Dashboard',
                    isOpen: false, // Controle de abertura do submenu
                    route: 'home'
                },
                {
                    icon: 'fas fa-chart-bar',
                    label: 'Relatórios',
                    isOpen: false, // Controle de abertura do submenu
                    subItems: [
                        { label: 'Geral', route: 'home' },
                        { label: 'Equipe', route: 'home' },
                        { label: 'Semanal', route: 'home' },
                    ]
                },
                {
                    icon: 'fas fa-trophy',
                    label: 'Rankings',
                    isOpen: false, // Controle de abertura do submenu
                    subItems: [
                        { label: 'Corrida de Vendas', route: 'home' },
                        { label: 'Corrida de Agendamentos', route: 'home' },
                        { label: 'Top 3 Vendas', route: 'home' },
                        { label: 'Top 3 Agendamentos', route: 'home' },
                        { label: 'Top 3 Equipes', route: 'home' },
                    ]
                },
                {
                    icon: 'fas fa-briefcase',
                    label: 'Negócios',
                    isOpen: false, // Controle de abertura do submenu
                    subItems: [
                        { label: 'Pipeline', route: 'home' },
                        { label: 'Lista', route: 'home' },
                        { label: 'Calendario', route: 'home' },
                        { label: 'Agendamentos', route: 'home' },
                        { label: 'Produção', route: 'home' },
                        { label: 'Importar', route: 'home' },
                        { label: 'Simulador', route: 'home' },
                    ]
                },
                {
                    icon: 'fas fa-user',
                    label: 'Administrativo',
                    isOpen: false, // Controle de abertura do submenu
                    subItems: [
                        { label: 'Funcionários', route: 'home' },
                        { label: 'Vendas', route: 'home' },
                        { label: 'Equipes', route: 'home' },

                    ]
                },
                {
                    icon: 'fas fa-cog',
                    label: 'Configurações',
                    isOpen: false, // Controle de abertura do submenu
                    subItems: [
                        { label: 'Minha Conta', route: 'home' },
                        { label: 'Empresa', route: 'configuracoes' },

                    ]
                },
                { icon: 'fas fa-sign-out-alt', label: 'Logout', method: 'post', route: 'logout' },
            ],
        };
    },
    methods: {
        toggleSidebar() {
            this.sidebarExpanded = !this.sidebarExpanded;
        },
        hoverSidebar(state) {
            this.isHovered = state;
        },
        // Método para alternar o submenu
        toggleSubmenu(index) {
            this.menuItems[index].isOpen = !this.menuItems[index].isOpen;
        },
    },
};
</script>

<style scoped>
.red-drop-shadow[data-v-215352a0] {
    box-shadow: 4px -4px 13px 1px rgb(22 21 21 / 40%), -8px 10px 12px 9px rgb(137 137 137 / 30%);
}
</style>
