<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

// This is the form of the frontend used for reactive forms and handling/errors
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <Head title="Log in" />
  <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-green-100 via-white to-blue-100 py-8 px-2">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border-t-4 border-green-400">
      <div class="flex flex-col items-center mb-6">
        <img src="/images/landseek_logo.png" alt="LandSeek Logo" class="w-16 h-16 rounded-full shadow border-2 border-green-400 mb-2 bg-white" />
        <h2 class="text-2xl font-bold text-green-700 tracking-tight">Welcome back to LandSeek</h2>
        <p class="text-gray-500 text-sm mt-1">Sign in to your account</p>
      </div>
      <div v-if="status" class="mb-4 font-medium text-sm text-green-600 text-center">
        {{ status }}
      </div>
      <form @submit.prevent="submit" class="space-y-5">
        <div>
          <InputLabel for="email" value="Email" />
          <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
            required
            autofocus
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>
        <div>
          <InputLabel for="password" value="Password" />
          <div class="relative">
            <TextInput
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              class="mt-1 block w-full pr-10"
              required
              autocomplete="current-password"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 focus:outline-none"
              tabindex="-1"
            >
              <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.212-3.592m3.243-2.298A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.51 5.569M3 3l18 18" />
              </svg>
            </button>
          </div>
          <InputError class="mt-2" :message="form.errors.password" />
        </div>
        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <Checkbox v-model:checked="form.remember" name="remember" />
            <span class="ms-2 text-sm text-gray-600">Remember me</span>
          </label>
          <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-green-600 hover:underline focus:outline-none">
            Forgot password?
          </Link>
        </div>
        <PrimaryButton class="w-full mt-2 flex justify-center items-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          <span class="w-full text-center">Log in</span>
        </PrimaryButton>
      </form>
      <div class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <Link :href="route('register')" class="text-green-700 font-semibold hover:underline ml-1">Register</Link>
      </div>
    </div>
  </div>
</template>
