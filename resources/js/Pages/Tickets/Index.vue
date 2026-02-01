<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const delForm = useForm({})

function destroyTicket(id) {
    if (!confirm('Tem certeza que deseja excluir este ticket?')) return
    delForm.delete(`/tickets/${id}`)
}

defineProps({
    tickets: Array,
})

const show = ref(false)
const previewUrl = ref('')
const previewName = ref('')

const previewKind = ref('')
const previewText = ref('')
const loading = ref(false)
const loadError = ref('')

function extFromPath(path) {
    const clean = (path || '').split('?')[0]
    const parts = clean.split('.')
    return (parts.length > 1 ? parts.pop() : '').toLowerCase()
}

function isImageExt(ext) {
    return ['png', 'jpg', 'jpeg', 'webp', 'gif'].includes(ext)
}

function isTextExt(ext) {
    return ['txt', 'json'].includes(ext)
}

async function openPreview(ticket) {
    if (!ticket.attachment_path) return

    previewUrl.value = `/storage/${ticket.attachment_path}`
    previewName.value = ticket.title
    show.value = true

    const ext = extFromPath(ticket.attachment_path)
    previewText.value = ''
    loadError.value = ''
    loading.value = false

    if (isImageExt(ext)) {
        previewKind.value = 'image'
        return
    }

    if (isTextExt(ext)) {
        previewKind.value = 'text'
        loading.value = true
        try {
            const res = await fetch(previewUrl.value)
            if (!res.ok) throw new Error(`HTTP ${res.status}`)

            const raw = await res.text()

            if (ext === 'json') {
                try {
                    previewText.value = JSON.stringify(JSON.parse(raw), null, 2)
                } catch {
                    previewText.value = raw
                }
            } else {
                previewText.value = raw
            }
        } catch (e) {
            loadError.value = 'Não foi possível carregar o arquivo.'
        } finally {
            loading.value = false
        }
        return
    }

    previewKind.value = 'text'
    previewText.value = 'Tipo de arquivo não suportado para preview no modal.'
}

function closePreview() {
    show.value = false
    previewUrl.value = ''
    previewName.value = ''
    previewKind.value = ''
    previewText.value = ''
    loading.value = false
    loadError.value = ''
}

function jobStatus(ticket) {
    return ticket?.detail?.technical_data?.status ?? '—'
}
</script>


<template>
    <AuthenticatedLayout>

        <Head title="Tickets" />

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <Link href="/dashboard" class="text-sm text-gray-600 underline hover:text-gray-900">
                                    ← Dashboard
                                </Link>
                                <h1 class="text-xl font-semibold text-gray-900">Tickets</h1>
                            </div>

                            <Link href="/tickets/create"
                                class="inline-flex items-center rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                                Novo
                            </Link>
                        </div>

                        <div class="mt-6">
                            <div v-if="tickets.length === 0"
                                class="rounded-md border border-gray-200 p-4 text-sm text-gray-600">
                                Nenhum ticket cadastrado ainda.
                            </div>

                            <div v-else class="overflow-hidden rounded-md border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">Ações</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">ID</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">Título</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">Projeto</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">Job</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-600">Anexo</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="t in tickets" :key="t.id" class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <button type="button"
                                                    class="text-sm text-red-600 underline hover:text-red-800"
                                                    @click="destroyTicket(t.id)">
                                                    Excluir
                                                </button>
                                            </td>

                                            <td class="px-4 py-3 text-gray-700">{{ t.id }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ t.title }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ t.project?.name ?? '—' }}</td>

                                            <td class="px-4 py-3">
                                                <span
                                                    class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                                                    :class="t.status === 'open'
                                                        ? 'bg-green-50 text-green-700'
                                                        : 'bg-gray-100 text-gray-700'">
                                                    {{ t.status }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-3 text-gray-700">
                                                {{ jobStatus(t) }}
                                            </td>

                                            <td class="px-4 py-3">
                                                <button v-if="t.attachment_path" type="button" @click="openPreview(t)"
                                                    class="text-sm underline text-gray-900 hover:text-gray-700">
                                                    Preview
                                                </button>
                                                <span v-else class="text-gray-400">—</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <p class="mt-3 text-xs text-gray-500">
                                * Lista filtrada para a empresa do usuário logado.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
            @click="closePreview">
            <div class="w-full max-w-3xl rounded-lg bg-white shadow" @click.stop>
                <div class="flex items-center justify-between border-b px-4 py-3">
                    <div class="text-sm font-semibold text-gray-900">Preview: {{ previewName }}</div>
                    <button class="text-sm underline" @click="closePreview">Fechar</button>
                </div>

                <div class="p-4">
                    <img v-if="previewKind === 'image'" :src="previewUrl" class="max-h-[70vh] w-full object-contain" />

                    <div v-else>
                        <div v-if="loading" class="text-sm text-gray-600">
                            Carregando…
                        </div>

                        <div v-else-if="loadError" class="text-sm text-red-600">
                            {{ loadError }}
                        </div>

                        <pre v-else
                            class="max-h-[70vh] overflow-auto rounded-md bg-gray-50 p-3 text-xs text-gray-800">{{ previewText }}</pre>
                    </div>

                    <div class="mt-3">
                        <a :href="previewUrl" target="_blank" class="text-sm underline text-gray-900">
                            Abrir em nova aba
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
