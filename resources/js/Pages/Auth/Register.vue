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

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="role" value="Role" />
                <select
                    id="role"
                    v-model="form.role"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
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

            <div class="mt-4">
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

            <!-- This is the password field input with strength meter -->
            <div class="mt-4 relative">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    class="mt-1 block w-full pr-20"
                    required
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    class="absolute top-9 right-2 text-sm text-gray-600 hover:text-gray-900"
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

            <!-- Confirm Password -->
            <div class="mt-4 relative">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    class="mt-1 block w-full pr-20"
                    required
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    class="absolute top-9 right-2 text-sm text-gray-600 hover:text-gray-900"
                    @click="showConfirmPassword = !showConfirmPassword"
                >
                    {{ showConfirmPassword ? 'Hide' : 'Show' }}
                </button>
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
                        <div class="ms-2">
                            I agree to the
                            <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a>
                            and
                            <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
