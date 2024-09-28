<script setup>
import { useForm } from '@inertiajs/vue3'
import TextInput from '../Components/TextInput.vue';
import LoginLayout from '../../Layouts/LoginLayout.vue';
import { Head, Link } from '@inertiajs/vue3'

defineOptions({ layout: LoginLayout })

const form = useForm({
    email: null,
    password: null,
    remember: null
})

const submit = () => {
    form.post(route('login'), {
        onError: () => form.reset('password', 'remember')
    })
}
</script>

<template>

    <Head title="Login" />
    <div class="min-h-screen flex">
        <!-- Coluna da esquerda: Formulário -->
        <div class="flex flex-col justify-center w-full md:w-1/2 lg:w-1/3 px-8 py-12 bg-white shadow-lg rounded-lg">
            <div class="mb-8 text-center">
                <img src="https://placehold.co/150x50" alt="Logo" class="w-32 mx-auto">
            </div>
            <h2 class="text-2xl font-bold text-center mb-6">Entrar</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <TextInput name="email" type="email" v-model="form.email" placeholder="Email"
                        :message="form.errors.email" class="w-full px-4 py-2 rounded-lg" />
                </div>
                <div class="mb-4">
                    <TextInput name="password" type="password" v-model="form.password" placeholder="Senha"
                        :message="form.errors.password" class="w-full px-4 py-2 rounded-lg" />
                </div>
                <div class="flex items-center justify-between mb-4">
                    <label for="remember" class="flex items-center">
                        <input type="checkbox" v-model="form.remember" id="remember" class="mr-2">
                        <span>Lembre-me</span>
                    </label>
                    <Link href="#" class="text-sm text-blue-500 hover:underline">Esqueceu sua senha?</Link>
                </div>
                <button
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-300">
                    Entrar
                </button>
            </form>
        </div>

        <!-- Coluna da direita: Imagem de destaque -->
        <div class="hidden md:block md:w-1/2 lg:w-2/3 relative">
            <img src="https://placehold.co/1500x500" alt="Imagem de destaque" class="object-cover w-full h-full">
        </div>
    </div>
</template>
