<template>
    <div v-if="show" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded shadow-lg w-1/3">
                <h3 class="text-lg font-semibold mb-4">{{ localItem.id ? 'Editar' : 'Adicionar' }} Produção</h3>
                <form @submit.prevent="save">
                    <!-- Nome da Produção -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nome</label>
                        <input v-model="localItem.name"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            type="text" required>
                    </div>

                    <!-- Data de Início -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Data de Início</label>
                        <input v-model="localItem.start_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            type="date" required>
                    </div>

                    <!-- Data de Fim -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Data de Fim</label>
                        <input v-model="localItem.end_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            type="date" required>
                    </div>

                    <!-- Toggle para marcar como Produção Ativa -->
                    <div class="mb-4" v-if="localItem.id">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Produção Ativa</label>
                        <div
                            class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" v-model="localItem.active" id="toggle"
                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                            <label for="toggle"
                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                        </div>
                        <span class="text-gray-600">{{ localItem.active ? 'Ativa' : 'Inativa' }}</span>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Salvar</button>
                        <button type="button" @click="$emit('close')"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,   // Para controlar a visibilidade do modal
    item: Object     // O item a ser editado ou um novo item
});
const emit = defineEmits(['close', 'success']);  // Emitir eventos para fechar o modal e notificar o sucesso

const localItem = ref({});

// Atualiza o localItem com o item a ser editado ou reseta para um novo item
watch(() => props.item, (newValue) => {
    localItem.value = { ...newValue } || { name: '', start_date: '', end_date: '', active: false };
}, { immediate: true });

// Função para salvar ou atualizar a produção
const save = () => {
    if (localItem.value.id) {
        // Editar item existente via PUT
        router.put(route('productions.update', { id: localItem.value.id }), localItem.value, {
            onSuccess: () => {
                emit('success');  // Notifica que foi salvo com sucesso
                emit('close');    // Fecha o modal
            },
            onError: (error) => {
                console.error('Erro ao atualizar produção:', error);
            }
        });
    } else {
        // Criar novo item via POST
        router.post(route('productions.store'), localItem.value, {
            onSuccess: () => {
                emit('success');  // Notifica que foi criado com sucesso
                emit('close');    // Fecha o modal
            },
            onError: (error) => {
                console.error('Erro ao adicionar produção:', error);
            }
        });
    }
};
</script>

<style scoped>
/* Estilo do toggle */
.toggle-checkbox:checked {
    right: 0;
    border-color: #68D391;
    /* Verde claro */
    background-color: #68D391;
}

.toggle-checkbox:checked+.toggle-label {
    background-color: #68D391;
}

.toggle-checkbox {
    right: 0.25rem;
    border: 1px solid #e5e7eb;
}

.toggle-label {
    width: 3rem;
    height: 1.5rem;
    background-color: #d1d5db;
    /* Cinza claro */
}
</style>
