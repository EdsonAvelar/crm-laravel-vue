<template>
    <div>
        <div class="flex justify-between mb-4">
            <div class="w-1/4">
                <input type="search" placeholder="Search" v-model="search" @input="fetchData">
            </div>
            <button @click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Adicionar Produção
            </button>
        </div>

        <!-- Modal para adicionar produção -->
        <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-6 rounded shadow-lg w-1/3">
                    <h3 class="text-lg font-semibold mb-4">Adicionar Produção</h3>
                    <form @submit.prevent="addProduction">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nome da Produção</label>
                            <input v-model="newProduction.name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                type="text" placeholder="Nome da produção">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
                            <textarea v-model="newProduction.description"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Descrição da produção"></textarea>
                        </div>
                        <div class="flex justify-between">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Salvar
                            </button>
                            <button @click="closeModal"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Renderizar a tabela de carregamento enquanto os dados estão vindo -->
        <table v-if="loading">
            <thead>
                <tr class="bg-slate-300">
                    <th v-for="column in columns" :key="column.label">
                        {{ column.label }}
                    </th>
                    <th>Ações</th> <!-- Coluna para as ações -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="n in 5" :key="n">
                    <td v-for="column in columns" :key="column.label">
                        <div class="loading-skeleton w-full h-4"></div>
                    </td>
                    <td>
                        <div class="loading-skeleton w-full h-4"></div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Tabela real -->
        <table v-else>
            <thead>
                <tr class="bg-slate-300">
                    <th v-for="column in columns" :key="column.label">
                        {{ column.label }}
                    </th>
                    <th>Ações</th> <!-- Coluna de ações para editar e deletar -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in data" :key="row.id">
                    <td v-for="column in columns" :key="column.field">
                        <!-- Renderiza a imagem para o campo Avatar -->
                        <template v-if="column.field === 'avatar'">
                            <img :src="'/storage/' + row[column.field]" alt="Avatar" class="w-12 h-12 rounded-full" />
                        </template>

                        <!-- Se houver função de formatação, aplica -->
                        <template v-else-if="column.format">
                            {{ column.format(row[column.field]) }}
                        </template>

                        <!-- Renderiza o valor normal se não houver formatação -->
                        <template v-else>
                            {{ row[column.field] }}
                        </template>
                    </td>
                    <!-- Coluna de ações -->
                    <td>
                        <button @click="editItem(row)" class="text-blue-500 hover:text-blue-700">Editar</button>
                        <button @click="deleteItem(row)" class="text-red-500 hover:text-red-700 ml-2">Deletar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-if="paginator">
            <PaginationLinks :paginator="paginator" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import PaginationLinks from './PaginationLinks.vue';
import { router } from "@inertiajs/vue3";
import { debounce } from 'lodash';

const props = defineProps({
    routeName: { type: String, required: true }, // Nome da rota a ser usada para buscar os dados
    columns: { type: Array, required: true }, // Colunas da tabela
    dataName: { type: String, required: true } // Nome do array que será retornado, ex: 'productions'
});

const data = ref([]); // Dados da tabela
const paginator = ref(null); // Dados de paginação, caso exista
const loading = ref(true);  // Controla o estado de carregamento
const search = ref('');
const showModal = ref(false); // Controla a visibilidade do modal
const newProduction = ref({
    name: '',
    description: ''
}); // Armazena os dados da nova produção

// Função para buscar os dados
const fetchData = debounce(() => {
    loading.value = true;
    router.get(route(props.routeName), { search: search.value }, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            data.value = page.props[props.dataName].data; // Ajusta para a rota e dados dinâmicos
            paginator.value = page.props[props.dataName]; // Paginação dinâmica
            loading.value = false;
        },
        onError: () => {
            loading.value = false;
        }
    });
}, 500);

// Função para editar um item
const editItem = (item) => {
    // Redireciona para a página de edição usando Inertia.js
    router.visit(route('edit', { id: item.id }));
};

// Função para deletar um item
const deleteItem = (item) => {
    if (confirm(`Tem certeza que deseja deletar o item ${item.name}?`)) {
        router.delete(route('delete', { id: item.id }), {
            onSuccess: () => {
                // Após deletar, buscar os dados novamente
                fetchData();
            }
        });
    }
};

// Função para abrir o modal
const openModal = () => {
    showModal.value = true;
};

// Função para fechar o modal
const closeModal = () => {
    showModal.value = false;
};

// Função para adicionar uma nova produção
const addProduction = () => {
    // Aqui você pode fazer uma requisição para salvar a nova produção no servidor
    console.log('Adicionando nova produção:', newProduction.value);

    // Fechar o modal após salvar
    closeModal();

    // Limpar os campos do formulário
    newProduction.value = {
        name: '',
        description: ''
    };

    // Você pode chamar a função fetchData aqui para atualizar a tabela
};
onMounted(() => {
    fetchData();
});
</script>

<style scoped>
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

/* Estilos do modal */
.fixed {
    @apply inset-0 z-10 bg-black bg-opacity-50;
}

.bg-white {
    @apply rounded-lg shadow-xl p-6;
}

button {
    @apply py-2 px-4 rounded;
}
</style>
