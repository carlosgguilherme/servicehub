<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Link, Head } from '@inertiajs/vue3'

const form = useForm({ name: '' })

function submit() {
    form.post('/companies')
}
</script>

<template>
    <AuthenticatedLayout>

        <Head title="..." />
        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white p-6 shadow">

                    <div>
                        <h1>Nova Empresa</h1>

                        <Link href="/tickets" class="text-sm underline">← Voltar</Link>

                        <form @submit.prevent="submit">
                            <input v-model="form.name" placeholder="Nome">

                            <button type="submit">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
