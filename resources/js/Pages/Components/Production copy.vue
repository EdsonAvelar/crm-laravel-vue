<template>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Gerenciamento de Produções</h1>

        <button @click="openModal" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nova Produção
        </button>

        <div class="overflow-x-auto mt-6">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-left">
                        <th class="py-3 px-4 border-b">Nome da Produção</th>
                        <th class="py-3 px-4 border-b">Data de Início</th>
                        <th class="py-3 px-4 border-b">Data de Fim</th>
                        <th class="py-3 px-4 border-b">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="production in productions" :key="production.id" class="hover:bg-gray-100">
                        <td class="py-3 px-4 border-b">{{ production.name }}</td>
                        <td class="py-3 px-4 border-b">{{ production.start_date }}</td>
                        <td class="py-3 px-4 border-b">{{ production.end_date }}</td>
                        <td class="py-3 px-4 border-b flex space-x-2">
                            <button @click="editProduction(production)" class="text-yellow-500 hover:text-yellow-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-3.536-3.536L8.636 12.414a2 2 0 00-.707 1.414v3.414a2 2 0 002 2h3.414a2 2 0 001.414-.707l6.364-6.364a2 2 0 00-3.414-2.828z">
                                    </path>
                                </svg>
                            </button>
                            <button @click="confirmDelete(production.id)" class="text-red-500 hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12H9m6 0l-3 3m0-3l3-3"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal para Criar/Editar Produção -->
        <transition name="fade">
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Editar Produção' : 'Nova Produção' }}</h2>
                    <form @submit.prevent="saveProduction">
                        <div class="mb-4">
                            <label class="block text-gray-700">Nome da Produção</label>
                            <input type="text" v-model="productionData.name"
                                class="border border-gray-300 rounded-lg w-full p-2" required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Data de Início</label>
                            <input type="date" v-model="productionData.start_date"
                                class="border border-gray-300 rounded-lg w-full p-2" required />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Data de Fim</label>
                            <input type="date" v-model="productionData.end_date"
                                class="border border-gray-300 rounded-lg w-full p-2" required />
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="closeModal"
                                class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg mr-2">Cancelar</button>
                            <button type="submit"
                                class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>

        <!-- Popup de Confirmação de Deleção -->
        <transition name="fade">
            <div v-if="isConfirmingDelete" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-bold mb-4">Confirmar Deleção</h2>
                    <p>Tem certeza que deseja deletar esta produção?</p>
                    <div class="flex justify-end mt-4">
                        <button type="button" @click="cancelDelete"
                            class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg mr-2">Cancelar</button>
                        <button @click="deleteProduction"
                            class="bg-red-500 text-white font-semibold py-2 px-4 rounded-lg">Deletar</button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';

export default {
    setup() {
        const productions = ref([]);
        const isModalOpen = ref(false);
        const isEditing = ref(false);
        const isConfirmingDelete = ref(false);
        const productionData = ref({
            id: null,
            name: '',
            start_date: '',
            end_date: ''
        });
        const productionIdToDelete = ref(null);

        const fetchProductions = async () => {
            try {
                // Requisição para buscar produções
                const response = await axios.get('/home'); // Ajuste a URL conforme necessário
                productions.value = response.data.data; // Acesse os dados
                console.log(productions.value);
            } catch (error) {
                console.error("Erro ao buscar produções:", error);
            }
        };

        const openModal = () => {
            isEditing.value = false;
            productionData.value = { id: null, name: '', start_date: '', end_date: '' };
            isModalOpen.value = true;
        };

        const editProduction = (production) => {
            productionData.value = { ...production };
            isEditing.value = true;
            isModalOpen.value = true;
        };

        const saveProduction = async () => {
            const url = isEditing.value
                ? route('productions.update', productionData.value.id)
                : route('productions.store');

            try {
                const method = isEditing.value ? 'put' : 'post';

                console.log(method)

                // Faz a chamada usando Axios
                await axios[method](url, {
                    name: productionData.value.name,
                    start_date: productionData.value.start_date,
                    end_date: productionData.value.end_date
                });

                closeModal(); // Fecha o modal após a operação
                fetchProductions(); // Recarrega as produções após a operação
            } catch (error) {
                console.error("Erro ao salvar produção:", error);
            }
        };


        const closeModal = () => {
            isModalOpen.value = false;
        };

        const confirmDelete = (id) => {
            productionIdToDelete.value = id;
            isConfirmingDelete.value = true;
        };

        const cancelDelete = () => {
            isConfirmingDelete.value = false;
        };

        const deleteProduction = () => {
            Inertia.delete(route('productions.destroy', productionIdToDelete.value));
            isConfirmingDelete.value = false;
        };

        onMounted(fetchProductions);

        return {
            productions,
            openModal,
            editProduction,
            saveProduction,
            closeModal,
            confirmDelete,
            cancelDelete,
            deleteProduction,
            isModalOpen,
            isEditing,
            isConfirmingDelete,
            productionData,
        };
    },
};
</script>

<style scoped>
/* Adicione estilos adicionais conforme necessário */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to

/* .fade-leave-active em versões <2.1.8 */
    {
    opacity: 0;
}
</style>
