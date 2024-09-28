<script setup>

import { useForm } from '@inertiajs/vue3'
import TextInput from '../Components/TextInput.vue';
import LoginLayout from '../../Layouts/LoginLayout.vue';

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

    <Head title="| Login" />
    <h1 class="title">Log In</h1>
    <div class="w-2/3 mx-auto">

        <form @submit.prevent="submit">

            <TextInput name="email" type="email" v-model="form.email" :message="form.errors.email" />

            <TextInput name="password" type="password" v-model="form.password" :message="form.errors.password" />

            <div class="flex items-center gap-2">
                <label for="remember"> Remember me</label>
                <input type="checkbox" v-model="form.remember" id="remember">
            </div>
            <div>
                <p class="text-green-900 mb-2 text-sm">Não tem conta ? <Link :href="route('register')"
                        class="text-link">Cadastre-se</Link></p>
                <button class="primary-btn">Log In</button>
            </div>

        </form>
    </div>
</template>