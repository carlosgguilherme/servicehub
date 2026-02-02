<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { statusLabel, statusPillClasses } from '@/utils/status'

const statuses = [
  { key: 'open', label: 'Aberto' },
  { key: 'in_progress', label: 'Em solução' },
  { key: 'closed', label: 'Fechado' },
]
defineProps({
    stats: Object,
    projects: Array,
    tickets: Array,
})

function processedStatus(ticket) {
    const status = ticket?.detail?.technical_data?.status
    if (!status) return '—'
    return status
}
</script>

<template>
    <AuthenticatedLayout>

        <Head title="Dashboard" />

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h1 class="text-2xl font-semibold mb-6">Dashboard — ServiceHub</h1>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-8">
                    <div class="rounded-lg bg-white p-4 shadow">
                        <div class="text-sm text-gray-500">Projetos (total)</div>
                        <div class="text-2xl font-bold">
                            {{ stats.projects_total ?? 0 }}
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-4 shadow">
                        <div class="text-sm text-gray-500">Tickets da empresa</div>
                        <div class="text-2xl font-bold">
                            {{ stats.tickets_total ?? 0 }}
                        </div>
                    </div>

                </div>


                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-lg bg-white p-4 shadow">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-semibold">Últimos tickets</h2>
                            <div class="flex gap-3">
                                <Link class="text-sm underline" href="/tickets">Ver todos</Link>
                                <Link class="text-sm underline" href="/tickets/create">Novo</Link>
                            </div>
                        </div>

                        <div v-if="tickets.length === 0" class="text-sm text-gray-500">
                            Você ainda não criou tickets.
                        </div>

                        <table v-else class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500">
                                    <th class="py-2">Título</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Projeto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="t in tickets" :key="t.id" class="border-t">
                                    <td class="py-2">
                                        {{ t.title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1"
                                            :class="statusPillClasses(t.status)">
                                            {{ statusLabel(t.status) }}
                                        </span>
                                    </td>
                                    <td class="py-2">
                                        {{ t.project?.name ?? '—' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="rounded-lg bg-white p-4 shadow">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-semibold">Projetos</h2>
                            <div class="flex gap-3">
                                <Link class="text-sm underline" href="/projects">Ver todos</Link>
                                <Link class="text-sm underline" href="/projects/create">Novo</Link>
                            </div>
                        </div>

                        <div v-if="projects.length === 0" class="text-sm text-gray-500">
                            Nenhum projeto cadastrado.
                        </div>

                        <ul v-else class="space-y-2">
                            <li v-for="p in projects" :key="p.id"
                                class="flex items-center justify-between border rounded px-3 py-2">
                                <span>{{ p.name }}</span>
                                <Link class="text-sm underline" :href="`/projects`">Abrir</Link>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
