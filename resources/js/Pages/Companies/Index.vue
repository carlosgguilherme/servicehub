<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({ companies: Array })
</script>

<template>
    <AuthenticatedLayout>

        <Head title="..." />
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <Link href="/dashboard" class="text-sm underline">← Dashboard</Link>
                <h1 class="text-xl font-semibold">Tickets</h1>
            </div>

            <Link href="/tickets/create" class="rounded bg-gray-900 px-3 py-2 text-sm text-white">
                Novo
            </Link>
        </div>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white p-6 shadow">

                    <div>
                        <h1>Companies</h1>

                        <Link href="/companies/create">Nova Empresa</Link>

                        <table border="1">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                            </tr>

                            <tr v-for="c in companies" :key="c.id">
                                <td>{{ c.id }}</td>
                                <td>{{ c.name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
