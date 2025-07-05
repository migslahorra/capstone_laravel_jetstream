<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
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

// Method 
// Password strength logic
// This determines and measures the strength of the password input 
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
  <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-green-600 to-green-400">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 mt-8 mb-8">
      <div class="flex flex-col items-center mb-8">
        <img src="/image/landseek_logo.jpg" alt="LandSeek Logo" class="h-16 w-16 rounded-full border-2 border-green-700 bg-white shadow mb-2" />
        <h1 class="text-3xl font-extrabold text-green-700 tracking-tight mb-1">Create your LandSeek Account</h1>
        <p class="text-gray-500 text-sm">Join the community and start your land journey!</p>
      </div>
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <InputLabel for="role" value="Role" />
          <select
            id="role"
            v-model="form.role"
            class="mt-1 block w-full border border-green-300 rounded-lg shadow-sm focus:ring focus:ring-green-200 focus:border-green-500"
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
            class="mt-1 block w-full border border-green-300 rounded-lg"
            required
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>
        <div class="relative">
          <InputLabel for="password" value="Password" />
          <TextInput
            id="password"
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'"
            class="mt-1 block w-full border border-green-300 rounded-lg pr-20"
            required
            autocomplete="new-password"
          />
          <button
            type="button"
            class="absolute top-9 right-2 text-sm text-green-700 hover:text-green-900"
            @click="showPassword = !showPassword"
          >
            {{ showPassword ? 'Hide' : 'Show' }}
          </button>
          <InputError class="mt-2" :message="form.errors.password" />
          <div v-if="form.password" class="mt-2">
            <div class="h-2 rounded-full" :class="[strengthColor, 'transition-all']"></div>
            <p class="text-sm mt-1 text-gray-600">Strength: <strong>{{ passwordStrength }}</strong></p>
          </div>
        </div>
        <div class="relative">
          <InputLabel for="password_confirmation" value="Confirm Password" />
          <TextInput
            id="password_confirmation"
            v-model="form.password_confirmation"
            :type="showConfirmPassword ? 'text' : 'password'"
            class="mt-1 block w-full border border-green-300 rounded-lg pr-20"
            required
            autocomplete="new-password"
          />
          <button
            type="button"
            class="absolute top-9 right-2 text-sm text-green-700 hover:text-green-900"
            @click="showConfirmPassword = !showConfirmPassword"
          >
            {{ showConfirmPassword ? 'Hide' : 'Show' }}
          </button>
          <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>
        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
          <InputLabel for="terms">
            <div class="flex items-center">
              <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
              <div class="ms-2">
                I agree to the
                <a target="_blank" :href="route('terms.show')" class="underline text-sm text-green-700 hover:text-green-900">Terms of Service</a>
                and
                <a target="_blank" :href="route('policy.show')" class="underline text-sm text-green-700 hover:text-green-900">Privacy Policy</a>
              </div>
            </div>
            <InputError class="mt-2" :message="form.errors.terms" />
          </InputLabel>
        </div>
        <div class="flex items-center justify-between mt-6">
          <Link :href="route('login')" class="underline text-sm text-green-700 hover:text-green-900">
            Already registered?
          </Link>
          <PrimaryButton class="ml-4 bg-gradient-to-r from-green-600 to-green-400 hover:from-green-700 hover:to-green-500 text-white font-bold px-6 py-2 rounded-lg shadow-lg transition" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Register
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
