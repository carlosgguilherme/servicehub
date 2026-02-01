<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
const page = usePage()

const delForm = useForm({})

function destroyProject(id) {
    if (!confirm('Tem certeza que deseja excluir este projeto?')) return
    delForm.delete(`/projects/${id}`)
}

defineProps({
    projects: Array,
})
</script>

<template>
    <AuthenticatedLayout>

        <Head title="Projetos" />
        <div v-if="page.props.flash?.error"
            class="mt-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ page.props.flash.error }}
        </div>

        <div v-if="page.props.flash?.success"
            class="mt-4 rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-700">
            {{ page.props.flash.success }}
        </div>


        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <Link href="/dashboard" class="text-sm text-gray-600 underline hover:text-gray-900">
                                    ← Dashboard
                                </Link>
                                <h1 class="text-xl font-semibold text-gray-900">Projetos</h1>
                            </div>

                            <Link href="/projects/create"
                                class="inline-flex items-center rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                                Novo
                            </Link>
                        </div>

                        <div class="mt-6">
                            <div v-if="projects.length === 0"
                                class="rounded-md border border-gray-200 p-4 text-sm text-gray-600">
                                Nenhum projeto cadastrado ainda.
                            </div>

                            <div v-else class="overflow-hidden rounded-md border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">Ações</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">ID</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">Nome</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="p in projects" :key="p.id" class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <button type="button"
                                                    class="text-sm text-red-600 underline hover:text-red-800"
                                                    @click="destroyProject(p.id)">
                                                    Excluir
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">{{ p.id }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ p.name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <p class="mt-3 text-xs text-gray-500">
                                * Você está vendo apenas projetos da sua empresa.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
