<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  projects: Array,
})

const form = useForm({
  title: '',
  project_id: '',
  notes: '',
  attachment: null,
})

function handleFile(e) {
  form.attachment = e.target.files?.[0] ?? null
}

const submit = () => {
  form.post('/tickets', { forceFormData: true })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Novo Ticket" />

    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
          <div class="p-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <Link href="/tickets" class="text-sm text-gray-600 underline hover:text-gray-900">
                  ← Voltar
                </Link>
                <h1 class="text-xl font-semibold text-gray-900">Novo Ticket</h1>
              </div>
            </div>

            <form class="mt-6 max-w-2xl" @submit.prevent="submit">
              <div>
                <InputLabel for="title" value="Título" />
                <TextInput
                  id="title"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.title"
                  required
                  autofocus
                  autocomplete="off"
                />
                <InputError class="mt-2" :message="form.errors.title" />
              </div>

              <div class="mt-4">
                <InputLabel for="project_id" value="Projeto" />
                <select
                  id="project_id"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  v-model="form.project_id"
                  required
                >
                  <option value="">Selecione</option>
                  <option v-for="p in projects" :key="p.id" :value="p.id">
                    {{ p.name }}
                  </option>
                </select>
                <InputError class="mt-2" :message="form.errors.project_id" />
              </div>

              <div class="mt-4">
                <InputLabel for="notes" value="Descrição / Notas" />
                <textarea
                  id="notes"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  rows="4"
                  v-model="form.notes"
                ></textarea>
                <InputError class="mt-2" :message="form.errors.notes" />
              </div>

              <div class="mt-4">
                <InputLabel for="attachment" value="Anexo (opcional) — até 5MB" />
                <input
                  id="attachment"
                  type="file"
                  class="mt-1 block w-full text-sm"
                  @change="handleFile"
                />
                <InputError class="mt-2" :message="form.errors.attachment" />
              </div>

              <div class="mt-6 flex items-center gap-3">
                <PrimaryButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }">
                  Salvar
                </PrimaryButton>

                <Link href="/dashboard" class="text-sm text-gray-600 underline hover:text-gray-900">
                  Ir para Dashboard
                </Link>
              </div>

              <p class="mt-3 text-xs text-gray-500">
                O ticket será vinculado ao usuário logado e ao projeto da sua empresa.
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
