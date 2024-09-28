<template>
    <div>
        <div class="flex justify-end mb-4">
            <div class="w-1/4">
                <input type="search" placeholder="Search" v-model="search" @input="fetchData">
            </div>
        </div>

        <!-- Renderizar a tabela de carregamento enquanto os dados estão vindo -->
        <table v-if="loading">
            <thead>
                <tr class="bg-slate-300">
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="n in 5" :key="n">
                    <td>
                        <div class="loading-skeleton w-12 h-12 rounded-full"></div>
                    </td>
                    <td>
                        <div class="loading-skeleton w-32 h-4"></div>
                    </td>
                    <td>
                        <div class="loading-skeleton w-48 h-4"></div>
                    </td>
                    <td>
                        <div class="loading-skeleton w-24 h-4"></div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Tabela real -->
        <table v-else>
            <thead>
                <tr class="bg-slate-300">
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users.data" :key="user.id">
                    <td><img class="avatar" :src="'storage/' + user.avatar" alt=""></td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ format(user.created_at) }}</td>
                </tr>
            </tbody>
        </table>

        <div>
            <PaginationLinks :paginator="users" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import PaginationLinks from './PaginationLinks.vue';
import { router } from "@inertiajs/vue3";
import { debounce } from 'lodash';

import { useToast } from "vue-toastification";

const toast = useToast();
const users = ref([]);
const loading = ref(true);  // Variável para controlar o carregamento
const search = ref('');

const format = (date) =>
    new Date(date).toLocaleDateString("en-us", {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

// Função para buscar os dados
const fetchData = debounce(() => {
    loading.value = true;  // Começa o carregamento
    router.get(route('users.index'), { search: search.value }, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            users.value = page.props.users;
            loading.value = false;  // Termina o carregamento
            toast.success("Tabela Carregada com sucesso");
        },
        onError: (error) => {
            loading.value = false;  // Termina o carregamento em caso de erro
            toast.error("Erro ao carregar os usuários" + (error || "Houve um erro na validação da imagem."));
        }
    });
}, 500);

// Carrega os dados ao montar o componente
onMounted(() => {
    fetchData();
});

</script>

<style scoped>
/* Estilos para o esqueleto de carregamento */
.loading-skeleton {
    @apply bg-slate-200 animate-pulse rounded;
}

table {
    @apply w-full text-sm text-left rounded-md shadow-lg overflow-hidden mb-6 table-auto bg-white;
}

thead {
    @apply text-xs uppercase font-medium border-b border-slate-100 text-slate-600;
}

tbody tr {
    @apply hover:bg-slate-100 w-full p-0;
}

th {
    @apply px-6 py-4;
}

td {
    @apply px-6 py-4 border-b border-slate-100;
}
</style>