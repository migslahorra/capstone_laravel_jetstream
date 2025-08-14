<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const passwordStrength = ref('');

const form = useForm({
  role: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
});

watch(() => form.password, (value) => {
  if (!value) {
    passwordStrength.value = '';
  } else if (value.length < 6) {
    passwordStrength.value = 'Weak';
  } else if (value.match(/[A-Z]/) && value.match(/[0-9]/) && value.match(/[^A-Za-z0-9]/)) {
    passwordStrength.value = 'Strong';
  } else {
    passwordStrength.value = 'Medium';
  }
});

const strengthColor = computed(() => {
  switch (passwordStrength.value) {
    case 'Weak': return 'bg-red-500';
    case 'Medium': return 'bg-yellow-500';
    case 'Strong': return 'bg-green-500';
    default: return 'bg-gray-300';
  }
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <Head title="Register" />
  <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-green-100 via-white to-blue-100 py-8 px-2">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border-t-4 border-green-400">
      <div class="flex flex-col items-center mb-6">
        <img src="/images/landseek_logo.png" alt="LandSeek Logo" class="w-16 h-16 rounded-full shadow border-2 border-green-400 mb-2 bg-white" />
        <h2 class="text-2xl font-bold text-green-700 tracking-tight">Create your LandSeek Account</h2>
        <p class="text-gray-500 text-sm mt-1">Join the community and start your land journey</p>
      </div>

      <form @submit.prevent="submit" class="space-y-5">
        <div>
          <InputLabel for="role" value="Role" />
          <select
            id="role"
            v-model="form.role"
            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-200 focus:border-green-500"
            required
            autofocus
          >
            <option value="" disabled>Select a role</option>
            <option value="Buyer">Buyer</option>
            <option value="Seller">Seller</option>
            <option value="Buyer and Seller">Buyer and Seller</option>
            <option value="Agent">Agent</option>
            <option value="All Around">All Around</option>
          </select>
          <InputError class="mt-2" :message="form.errors.role" />
        </div>

        <div>
          <InputLabel for="email" value="Email" />
          <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
            required
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
              autocomplete="new-password"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600"
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
          <div v-if="form.password" class="mt-2">
            <div class="h-2 rounded-full" :class="[strengthColor, 'transition-all']"></div>
            <p class="text-sm mt-1 text-gray-600">Strength: <strong>{{ passwordStrength }}</strong></p>
          </div>
        </div>

        <div>
          <InputLabel for="password_confirmation" value="Confirm Password" />
          <div class="relative">
            <TextInput
              id="password_confirmation"
              v-model="form.password_confirmation"
              :type="showConfirmPassword ? 'text' : 'password'"
              class="mt-1 block w-full pr-10"
              required
              autocomplete="new-password"
            />
            <button
              type="button"
              @click="showConfirmPassword = !showConfirmPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600"
              tabindex="-1"
            >
              <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
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
          <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
          <label class="flex items-center">
            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
            <span class="ms-2 text-sm text-gray-600">
              I agree to the
              <a target="_blank" :href="route('terms.show')" class="underline text-green-700 hover:text-green-900">Terms of Service</a>
              and
              <a target="_blank" :href="route('policy.show')" class="underline text-green-700 hover:text-green-900">Privacy Policy</a>
            </span>
          </label>
          <InputError class="mt-2" :message="form.errors.terms" />
        </div>

        <PrimaryButton class="w-full mt-2 flex justify-center items-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          <span class="w-full text-center">Register</span>
        </PrimaryButton>
      </form>

      <div class="mt-6 text-center text-sm text-gray-500">
        Already have an account?
        <Link :href="route('login')" class="text-green-700 font-semibold hover:underline ml-1">Log in</Link>
      </div>
    </div>
  </div>
</template>
