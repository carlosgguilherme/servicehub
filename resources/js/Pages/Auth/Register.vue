<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  company_name: '',
  phone: '',
  role: 'user', // padrão
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Criar conta" />

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="name" value="Nome" />

        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />

        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="E-mail" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Senha" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Confirmar senha" />

        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="mt-4">
        <InputLabel for="company_name" value="Empresa" />

        <TextInput
          id="company_name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.company_name"
          required
          autocomplete="organization"
        />

        <InputError class="mt-2" :message="form.errors.company_name" />
      </div>

      <div class="mt-4">
        <InputLabel for="phone" value="Telefone" />

        <TextInput
          id="phone"
          type="text"
          class="mt-1 block w-full"
          v-model="form.phone"
          autocomplete="tel"
          placeholder="(DDD) 99999-9999"
        />

        <InputError class="mt-2" :message="form.errors.phone" />
      </div>

      <div class="mt-4">
        <InputLabel for="role" value="Cargo" />

        <select
          id="role"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          v-model="form.role"
        >
          <option value="user">Usuário</option>
          <option value="agent">Suporte</option>
          <option value="admin">Administrador</option>
        </select>

        <InputError class="mt-2" :message="form.errors.role" />
      </div>

      <div class="mt-4 flex items-center justify-end">
        <Link
          :href="route('login')"
          class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          Já tem conta? Entrar
        </Link>

        <PrimaryButton
          class="ms-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Criar conta
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
