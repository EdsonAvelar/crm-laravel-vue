<template>
    <div v-if="isVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg">
            <div class="bg-blue-500 text-white text-2xl font-bold p-4 rounded-t-lg text-center">
                Cortar Imagem
            </div>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="p-2" action="" method="POST" enctype="multipart/form-data" id="cropForm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-12">
                                    <div v-if="cropping">

                                        <label class="block text-lg font-semibold text-gray-700 mb-2" for="inputImage">
                                            Faça o Upload da Sua Imagem <span class="text-red-500">*</span>
                                        </label>
                                        <input ref="inputImage" type="file" name="image" id="inputImage" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md 
               file:border-0 file:text-sm file:font-semibold 
               file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer">
                                    </div>

                                    <input id="editimage_user_id" name="user_id" hidden value=''>
                                    <input type="text" class="editar_equipe_id" name="editar_equipe_id" hidden value="">
                                    <input id="tipo_corte" hidden value="quadrado1">
                                    <input id="corte_largura" hidden value="300">
                                    <input id="tipo_altura" hidden value="300">
                                    <br>
                                    <div ref="imgContainer" class="img-container mt-3" style="display:none;">
                                        <img ref="cropperImage" id="cropperImage" class="img-fluid" src="#"
                                            alt="Imagem para cortar">
                                    </div>
                                    <input name="pasta_imagem" id="pasta_imagem" hidden />
                                    <input name="imagem_name" id="imagem_name" hidden />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button"
                                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                data-bs-dismiss="modal" id="cancelButton" @click="closeModal">Cancelar</button>
                            <button type="button" ref="cropButton"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition"
                                style="display:none;">
                                <i class="mdi mdi-crop"></i> Cortar
                            </button>
                            <button type="submit" ref="saveButton"
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition"
                                style="display:none;">
                                <i class="mdi mdi-content-save"></i> Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import 'cropperjs/dist/cropper.css'; // Importa o CSS do CropperJS


import { ref, onMounted, nextTick, defineExpose, watch } from 'vue';
import Cropper from 'cropperjs';

const inputImage = ref(null);
const cropperImage = ref(null);
const cropButton = ref(null);
const saveButton = ref(null);
const imgContainer = ref(null);
const cropping = ref(true)


const isVisible = ref(false);


// Função para abrir o modal
const openModal = () => {
    isVisible.value = true;
};

// Função para fechar o modal
const closeModal = () => {
    isVisible.value = false;
};


let cropper = null;

const resetCropper = () => {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    cropperImage.value.src = '';
    imgContainer.value.style.display = 'none';
    inputImage.value.value = '';
    saveButton.value.style.display = 'none';
    cropButton.value.style.display = 'none'; // Esconde o botão de cortar
};

// Função chamada quando uma nova imagem é selecionada
const handleImageChange = (event) => {
    const tipoCorte = document.getElementById('tipo_corte').value; // Proporção do corte
    let aspectRatio;

    switch (tipoCorte) {
        case 'quadrado':
            aspectRatio = 1;
            break;
        case 'retangular':
            aspectRatio = 30 / 9;
            break;
        case 'livre':
            aspectRatio = NaN;
            break;
        default:
            aspectRatio = 1;
    }

    const files = event.target.files;
    if (files && files.length > 0) {

        cropping.value = false;
        const reader = new FileReader();
        reader.onload = (e) => {
            cropperImage.value.src = e.target.result;
            imgContainer.value.style.display = 'block';
            cropButton.value.style.display = 'inline-block'; // Mostrar o botão "Cortar" quando a imagem é carregada

            cropper = new Cropper(cropperImage.value, {
                aspectRatio: aspectRatio,
                viewMode: 1,
                autoCropArea: 1,
                movable: true,
                zoomable: true,
                rotatable: true,
                scalable: true,
            });
        };
        reader.readAsDataURL(files[0]);
    }
};

// Função chamada ao clicar em "Cortar"
const handleCropImage = () => {
    if (cropper) {

        const canvas = cropper.getCroppedCanvas({
            width: document.getElementById('corte_largura').value,
            height: document.getElementById('tipo_altura').value,
        });

        cropperImage.value.src = canvas.toDataURL('image/png');
        cropper.destroy();
        imgContainer.value.style.display = 'flex'; // Certifique-se de que o contêiner permaneça flexível
        cropperImage.value.style.margin = '0 auto'; // Centraliza horizontalmente

        canvas.toBlob((blob) => {
            const newInput = document.createElement('input');
            newInput.type = 'hidden';
            newInput.name = 'croppedImage';
            newInput.value = URL.createObjectURL(blob);

            document.getElementById('cropForm').appendChild(newInput);
            saveButton.value.style.display = 'inline-block';
            cropButton.value.style.display = 'none';
        });

    }
};

// Expõe as funções para serem acessadas de fora
defineExpose({
    openModal,
    closeModal,
});

// Assiste mudanças na visibilidade do modal
watch(isVisible, async (newValue) => {
    if (newValue) {
        cropping.value = true;
        // Quando o modal for visível, adiciona os eventos
        await nextTick(); // Aguarda os elementos do DOM serem renderizados
        inputImage.value.addEventListener('change', handleImageChange);
        cropButton.value.addEventListener('click', handleCropImage);
    } else {
        // Quando o modal for fechado, remove os eventos
        if (inputImage.value) {
            inputImage.value.removeEventListener('change', handleImageChange);
        }
        if (cropButton.value) {
            cropButton.value.removeEventListener('click', handleCropImage);
        }
    }
});

</script>

<style scoped>
.img-container {
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 100%;
    max-height: 400px;
    overflow: hidden;
    margin: 0 auto;
}

#cropperImage {
    max-width: 100%;
    max-height: 100%;
    display: block;
}
</style>
