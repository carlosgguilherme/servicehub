<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

const form = useForm({
  name: '',
})

const submit = () => {
  form.post('/projects')
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Novo Projeto" />

    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
          <div class="p-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <Link href="/projects" class="text-sm text-gray-600 underline hover:text-gray-900">
                  ← Voltar
                </Link>
                <h1 class="text-xl font-semibold text-gray-900">Novo Projeto</h1>
              </div>
            </div>

            <form class="mt-6 max-w-xl" @submit.prevent="submit">
              <div>
                <InputLabel for="name" value="Nome do projeto" />

                <TextInput
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  required
                  autofocus
                  autocomplete="off"
                />

                <InputError class="mt-2" :message="form.errors.name" />
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
                O projeto será vinculado automaticamente à empresa do usuário logado.
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
