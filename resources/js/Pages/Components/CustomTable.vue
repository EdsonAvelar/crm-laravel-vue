<template>
    <div>
        <div class="flex justify-between mb-4" v-if="hasSlot">
            <div class="w-1/4">
                <input type="search" placeholder="Search" v-model="search" @input="fetchData">
            </div>
            <button @click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Adicionar
            </button>
        </div>

        <!-- Slot para o modal que será fornecido pelo componente pai -->
        <slot :showModal="showModal" :closeModal="closeModal" :item="currentItem" @success="fetchData"></slot>

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
import { ref, onMounted, computed, getCurrentInstance } from 'vue';
import PaginationLinks from './PaginationLinks.vue';
import { router } from "@inertiajs/vue3";
import { debounce } from 'lodash';

const props = defineProps({
    routeName: { type: String, required: true }, // Nome da rota a ser usada para buscar os dados
    columns: { type: Array, required: true }, // Colunas da tabela
    dataName: { type: String, required: true } // Nome do array que será retornado, ex: 'productions'
});

// Verifica se há slot fornecido
const { slots } = getCurrentInstance();  // Acessa os slots disponíveis no componente

// Verifica se há slot fornecido
const hasSlot = computed(() => !!slots.default);


const data = ref([]); // Dados da tabela
const paginator = ref(null); // Dados de paginação, caso exista
const loading = ref(true);  // Controla o estado de carregamento
const search = ref('');
const showModal = ref(false); // Controla a visibilidade do modal
const currentItem = ref(null); // Armazena o item que será editado ou criado

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
    currentItem.value = item;
    openModal();
};

// Função para deletar um item
const deleteItem = (item) => {
    if (confirm(`Tem certeza que deseja deletar o item ${item.name}?`)) {
        router.delete(route(props.dataName + '.delete', { id: item.id }), {
            onSuccess: () => {
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
    currentItem.value = null;
    fetchData();
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
</style>
