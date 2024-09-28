<template>
    <div>

        <form @submit.prevent="submit(route)">
            <!-- Simulação da imagem (componente clicável) -->
            <div class="image-preview" @click="openModal">
                <img :src="croppedImage || validSource" :alt="imageName" :width="width" :height="height"
                    :class="['image-zoom shadow-2xl', customclass]" />
                <!-- <div v-else class="placeholder">Clique para selecionar uma imagem</div> -->
            </div>

            <!-- Modal para selecionar e cortar a imagem -->
            <div v-if="isModalOpen" class="modal">
                <div class="modal-content ">
                    <div class="crop-container">
                        <img ref="image" :src="imageUrl" alt="Imagem para cortar" />
                    </div>
                    <div class="p-1 ml-auto m-2 ">
                        <button @click="cropImage" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg
                        shadow-md hover:bg-blue-600 transition duration-200">Cortar e Salvar</button>
                        <button @click="closeModal"
                            class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-gray-400 transition duration-200">Cancelar</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";
import { useToast } from "vue-toastification";
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    id: null,
    image: null,
    destination: null,
    name: null,
})

const toast = useToast();

export default {
    props: {
        width: {
            type: Number,
            default: 100
        },
        height: {
            type: Number,
            default: 100
        },
        f_width: {
            type: Number,
            default: 300
        },
        f_height: {
            type: Number,
            default: 200
        },
        route: {
            type: String,
            default: "/home"
        },
        source: {
            type: String,
            default: "https://placehold.co/600x400"
        },
        customclass: {
            type: String,
            default: ""
        }
    },
    data() {
        return {
            isModalOpen: false,
            imageUrl: null,
            croppedImage: null,
            cropper: null,
            imageName: "Imagem",
            aspectRatio: 0,
            validSource: null, // Para armazenar o source válido

        };
    },



    mounted() {
        this.checkImageSource(this.source);
    },
    methods: {
        checkImageSource(source) {
            const img = new Image();
            img.src = source;
            img.onload = () => {
                // A imagem existe
                this.validSource = source;
            };
            img.onerror = () => {
                // A imagem não existe, usa a imagem padrão
                this.validSource = "images/empresa/default.png"; // Substitua pela URL da imagem padrão
            };
        },
        openModal() {
            // Abre modal para selecionar imagem
            const input = document.createElement("input");
            input.type = "file";
            input.accept = "image/*";
            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {

                    const fileType = file.type.split('/')[0];
                    if (fileType !== 'image') {
                        alert("Por favor, selecione um arquivo de imagem.");
                        return;
                    }

                    if (this.aspectRatio === 0) {

                        console.log(this.f_height / (this.f_width | 1))
                        this.aspectRatio = this.f_width / (this.f_height | 0)
                        if (!this.aspectRatio) {
                            this.aspectRatio = 1;
                        }

                    }

                    this.imageUrl = URL.createObjectURL(file);
                    this.imageName = file.name;
                    this.isModalOpen = true;
                    this.$nextTick(() => {
                        const image = this.$refs.image;
                        this.cropper = new Cropper(image, {
                            aspectRatio: this.aspectRatio,
                            viewMode: 1
                        });
                    });
                }
            };
            input.click();
        },
        cropImage() {
            // Corta a imagem com base nos atributos definidos
            const canvas = this.cropper.getCroppedCanvas({
                width: this.f_width,
                height: this.f_height
            });

            this.croppedImage = canvas.toDataURL("image/png");

            // Salva a imagem e fecha o modal
            this.closeModal();

            // Converte o canvas para um blob (objeto de imagem)
            canvas.toBlob((blob) => {
                // Cria um objeto File a partir do blob
                const file = new File([blob], this.imageName, { type: "image/png" });

                // Atualiza o formulário com o arquivo
                form.image = file; // ou um nome de campo apropriado

                const parts = this.source.split('/');
                const imageName = parts.pop();
                const imagePathDestination = parts.join('/') + '/';

                form.destination = imagePathDestination;
                form.name = imageName;


                // Salva a imagem e fecha o modal
                this.closeModal();
                this.submit(this.route);
            }, "image/png");

        },
        closeModal() {
            this.isModalOpen = false;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }

            // Libere a URL do objeto após o uso
            if (this.imageUrl) {
                URL.revokeObjectURL(this.imageUrl);
                this.imageUrl = null;
            }
        },
        submit(route_name) {
            console.log("Submit")

            // form.image = this.croppedImage;
            // form.imageLocation = this.imageLocation;

            form.post(route(route_name), {
                onError: (errors) => {
                    toast.error("Erro: " + (form.errors.image || "Houve um erro na validação da imagem."));
                },
                onSuccess: () => {
                    toast.success("Imagem Salva com Sucesso!");
                }

            });
        },
    }
};
</script>

<style scoped>
.image-preview {
    /* width: 200px;
    height: 200px; */
    /* border: 2px solid #ccc; */
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.image-preview img {
    max-width: 100%;
    max-height: 100%;
}

.image-preview img.image-zoom:hover {
    transform: scale(1.1);
    transition: transform 0.3s ease;
    /* Aumenta a imagem em 10% */
}

.placeholder {
    color: #999;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {

    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}


.crop-container img {
    width: 50vw;

}

.rounded-full {
    border-radius: 100%;
    border: 5px solid #ccc;
}

.rectangle-full {
    border: 5px solid #ccc;
    border-radius: 10%;
}

.squared-full {
    border: 5px solid #ccc;

}
</style>