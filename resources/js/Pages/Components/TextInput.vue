<script setup>

const model = defineModel({
    type: null,
    required: true
})

defineProps({
    name: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'text'
    },
    message: String
})
</script>
<template>
    <div>
        <div class="mb-6">
            <label>{{ name }}</label>
            <input :type="type" v-model="model" :class="{ '!ring-red-500': message }" class="input-modern" />
            <small class="input-error" v-if="message">{{ message }}</small>
        </div>
    </div>
</template>

<style scoped>
/* Estilos para os campos de input */
.input-modern {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e5e7eb;
    /* Borda neutra inicial */
    border-radius: 8px;
    /* Bordas arredondadas */
    background-color: #f9fafb;
    /* Fundo claro */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    /* Sombra leve */
    font-size: 1rem;
    /* Tamanho do texto */
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    /* Transições suaves */
}

/* Quando o input está focado */
.input-modern:focus {
    border-color: #6366f1;
    /* Cor da borda roxa (moderna) no foco */
    box-shadow: 0 4px 6px rgba(99, 102, 241, 0.3);
    /* Sombra com cor de foco */
    background-color: #ffffff;
    /* Fundo branco ao focar */
    outline: none;
    /* Remove a borda padrão */
}

/* Estilos para mensagens de erro */
.input-error {
    border-color: #f87171;
    /* Cor da borda vermelha em caso de erro */
    background-color: #fef2f2;
    /* Fundo leve de erro */
}

/* Placeholder estilizado */
.input-modern::placeholder {
    color: #9ca3af;
    /* Cor do placeholder (neutro) */
    opacity: 1;
    transition: color 0.3s ease;
}
</style>
