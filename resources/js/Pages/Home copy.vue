<script setup>
import { ref, watch } from 'vue';
import PaginationLinks from './Components/PaginationLinks.vue';
import { router } from "@inertiajs/vue3"
import { debounce } from 'lodash';
defineProps({ users: Object });

const format = (date) =>
    new Date(date).toLocaleDateString("en-us", {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

const search = ref('');

// watch(search, debounce((q) => router.get('/', { search: q }, { preserveState: true }), 500));

watch(search, (q) => {
    router.get('/home', { search: q }, {
        preserveState: true, // Preserva o estado atual
        replace: true // Substitui a URL sem adicionar ao histórico
    });
});
</script>

<template>

    <Head :title="` | ${$page.component}`" />

    <div>
        <div class="flex justify-end mb-4">
            <div class="w-1/4">
                <input type="search" placeholder="Search" v-model="search">
            </div>
        </div>
        <table>
            <thead>
                <tr class="bg-slate-300">
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users.data" :key="user.id">
                    <td><img class="avatar" :src="'storage/' + user.avatar" alt=""></td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ format(user.created_at) }}</td>
                </tr>
            </tbody>
        </table>

        <div>
            <!-- <Link v-for="link in users.links" :key="link.label" v-html="link.label" :href="link.url" class="p-1 mx-1"
                :class="{ 'text-slate-300': !link.url, 'text-blue-500 font-medium': link.active }">

            </Link>
            <p class="text-skate-600 text-sm">Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
            </p> -->
            <PaginationLinks :paginator="users" />
        </div>
    </div>
</template>

<style scoped>
.title {
    @apply my-6 text-center text-3xl font-bold leading-9 text-slate-900;
}

.error {
    @apply text-xs text-red-500 mt-1;
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

.avatar {
    @apply w-8 h-8 rounded-full overflow-hidden object-center object-cover;
}
</style>