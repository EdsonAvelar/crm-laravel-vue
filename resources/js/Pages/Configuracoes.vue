<template>
    <div class="min-h-screen flex bg-gray-100">

        <!-- Menu Lateral -->
        <aside class="w-1/4 lg:w-1/5 bg-white shadow-lg">
            <div class="p-4">
                <h2 class="text-xl font-bold mb-6">Configurações</h2>
                <ul class="space-y-4">
                    <li>
                        <button @click="changeSection('artes')" :class="{ 'bg-gray-300': activeSection === 'artes' }"
                            class="w-full text-left bg-gray-200 hover:bg-gray-300 p-3 rounded-lg font-medium">
                            Artes
                        </button>
                    </li>
                    <li>
                        <button @click="changeSection('empresa')"
                            :class="{ 'bg-gray-300': activeSection === 'empresa' }"
                            class="w-full text-left bg-gray-200 hover:bg-gray-300 p-3 rounded-lg font-medium">
                            Empresa
                        </button>
                    </li>
                    <li>
                        <button @click="changeSection('crm')" :class="{ 'bg-gray-300': activeSection === 'crm' }"
                            class="w-full text-left bg-gray-200 hover:bg-gray-300 p-3 rounded-lg font-medium">
                            Configurações do CRM
                        </button>
                    </li>
                    <li>
                        <button @click="changeSection('producao')"
                            :class="{ 'bg-gray-300': activeSection === 'producao' }"
                            class="w-full text-left bg-gray-200 hover:bg-gray-300 p-3 rounded-lg font-medium">
                            Produção
                        </button>
                    </li>
                    <li>
                        <button @click="changeSection('marketing')"
                            :class="{ 'bg-gray-300': activeSection === 'marketing' }"
                            class="w-full text-left bg-gray-200 hover:bg-gray-300 p-3 rounded-lg font-medium">
                            Configurações de Marketing
                        </button>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <main class="w-3/4 lg:w-4/5 p-6">

            <!-- Seção de Artes -->
            <section v-if="activeSection === 'artes'" class="bg-white shadow-lg rounded-lg p-6">

                <h1 class="">Artes da Empresa
                </h1>
                <div class="grid grid-cols-4 gap-6">


                    <div class="flex flex-col items-center">
                        <label class="text-lg font-semibold mb-2">Logo Circular <span
                                class="flex text-sm text-slate-400">512px
                                x 512px (PNG)</span></label>


                        <CustomImage f_width="512" f_height="512" :aspectRatio="1" destination="logos"
                            name="empresa_logo_circular.png" route="empresa_images" width="100"
                            customclass='rounded-full ' source="/images/empresa/logos/empresa_logo_circular.png" />

                    </div>

                    <div class="flex flex-col items-center">
                        <label class="text-lg font-semibold mb-2">Logo Horizontal <span
                                class="flex text-sm text-slate-400">512px
                                x 256px (PNG)</span></label>

                        <CustomImage f_width="512" f_height="256" destination="logos" name="empresa_logo_circular.png"
                            route="empresa_images" width="150" height="50" customclass='rectangle-full'
                            source="/images/empresa/logos/empresa_logo_transparente.png" />

                    </div>



                    <div class="flex flex-col items-center">
                        <label class="text-lg font-semibold mb-2">Logo Horizontal <span
                                class="flex text-sm text-slate-400">512px
                                x 128px (PNG)</span></label>

                        <CustomImage f_width="512" f_height="128" destination="logos" name="empresa_logo_circular.png"
                            route="empresa_images" width="150" height="50" customclass='rectangle-full'
                            source="/images/empresa/logos/empresa_logo_horizontal.png" />

                    </div>



                    <div class="flex flex-col items-center">
                        <label class="text-lg font-semibold mb-2">Marca D'agua<span
                                class="flex text-sm text-slate-400">512px
                                x 256px (PNG)</span></label>

                        <CustomImage f_width="1000" f_height="1000" destination="logos" name="empresa_logo_circular.png"
                            route="empresa_images" width="150" height="150" customclass='rectangle-full'
                            source="/images/empresa/logos/empresa_logo_transparente.png" />

                    </div>



                </div>
            </section>


            <!-- Seção de Informações da Empresa -->
            <section v-if="activeSection === 'empresa'" class="bg-white shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Informações da Empresa</h1>
                <!-- Formulário de informações da empresa -->
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="text-lg font-semibold">Nome da Empresa</label>
                        <input type="text" class="w-full p-3 mt-2 border rounded-lg"
                            placeholder="Digite o nome da empresa">
                    </div>
                    <div>
                        <label class="text-lg font-semibold">Endereço</label>
                        <input type="text" class="w-full p-3 mt-2 border rounded-lg" placeholder="Digite o endereço">
                    </div>
                    <div>
                        <label class="text-lg font-semibold">Telefone</label>
                        <input type="text" class="w-full p-3 mt-2 border rounded-lg" placeholder="Digite o telefone">
                    </div>
                </div>
            </section>

            <!-- Seção de Configurações do CRM -->
            <section v-if="activeSection === 'crm'" class="bg-white shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Configurações do CRM</h1>
                <!-- Configurações do CRM -->
                <p>Aqui irão as configurações do CRM...</p>
            </section>

            <section v-if="activeSection === 'producao'" class="bg-white shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Produção</h1>
                <!-- Configurações do CRM -->

                <!-- 
                <CustomTable routeName="users.index" dataName="users" :columns="[
                    { label: 'Avatar', field: 'custom_image', src: 'images/empresa/default.png', class: 'w-12 h-12 rounded-full' },
                    { label: 'Name', field: 'name' },
                    { label: 'Email', field: 'email' },
                    { label: 'Registration Date', field: 'created_at', format: (date) => new Date(date).toLocaleDateString() }
                ]" /> -->

                <CustomTable routeName="productions.index" dataName="productions" :columns="[
                    { label: 'Active', field: 'active' },
                    { label: 'Name', field: 'name' },
                    { label: 'Inicio', field: 'start_date', format: (date) => new Date(date).toLocaleDateString() },
                    { label: 'Fim', field: 'end_date', format: (date) => new Date(date).toLocaleDateString() },

                ]">

                    <!-- Modal fornecido via slot -->
                    <template #default="{ showModal, closeModal, item, success }">
                        <MyModal :show="showModal" @close="closeModal" :item="item" @success="success" />
                    </template>

                </CustomTable>
            </section>

            <!-- Seção de Configurações de Marketing -->
            <section v-if="activeSection === 'marketing'" class="bg-white shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Configurações de Marketing</h1>
                <!-- Configurações de Marketing -->
                <p>Aqui irão as configurações de Marketing...</p>
            </section>
        </main>

        <ImageCropper ref="imageCropper" />
    </div>


</template>

<script setup>
import { ref } from 'vue';
import ImageCropper from '../Pages/Components/ImageCropper.vue';
import CustomImage from './Components/CustomImage.vue';
import Producao from '../Pages/Components/Production.vue';

import CustomTable from '../Pages/Components/CustomTable.vue';

import MyModal from './Components/ProductionModal.vue';

const fetchData = ref(null); // Ref para armazenar a função de atualizar dados

// Função para capturar a função fetchData do CustomTable
const captureFetchData = (fn) => {
    fetchData.value = fn;
};


const imageCropper = ref(null); // Referência ao componente

// Função para abrir o modal de corte de imagem
const openCropperModal = () => {
    imageCropper.value.openModal(); // Chama a função openModal dentro do modal
};


// // Função para abrir o modal de corte de imagem
const openCropper = (title) => {
    // modalTitle.value = title;
    imageCropper.value.openModal();
};

// // Função para fechar o modal
// const closeCropper = () => {
//     showCropper.value = false;
// };


// Variável para controlar qual seção está ativa
const activeSection = ref('artes');

// Função para alterar a seção ativa
const changeSection = (section) => {
    activeSection.value = section;
};
</script>

<style scoped>
h1 {
    @apply bg-blue-950 text-white text-2xl font-bold mb-3 rounded-t-lg text-center;
}

/* Estilos adicionais podem ser adicionados conforme necessário */
.btn {
    background-color: #6366f1;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #4f46e5;
}
</style>
