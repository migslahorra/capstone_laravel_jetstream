<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: { type: String, required: true },
  phpVersion: { type: String, required: true },
});

function handleImageError() {
  document.getElementById('screenshot-container')?.classList.add('!hidden');
  document.getElementById('docs-card')?.classList.add('!row-span-1');
  document.getElementById('docs-card-content')?.classList.add('!flex-row');
  document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
  <Head title="Welcome to LandSeek" />
  <div class="bg-gradient-to-br from-green-100 via-white to-blue-100 min-h-screen flex flex-col">
    <!-- HERO -->
    <header class="w-full py-12 px-4 flex flex-col items-center justify-center bg-white shadow-md">
      <img src="/images/landseek_logo.png" alt="LandSeek Logo" class="w-24 h-24 rounded-full shadow-lg border-4 border-green-400 mb-4" />
      <h1 class="text-4xl md:text-5xl font-extrabold text-green-700 tracking-tight text-center">LandSeek</h1>
      <p class="mt-3 text-lg md:text-2xl text-gray-700 text-center max-w-2xl">
        A Digital Marketplace for Land Hunting
      </p>
      <p class="mt-2 text-base text-gray-500 text-center max-w-xl">
        Discover, list, and connect with land buyers, sellers, and agents in a secure, modern, and vibrant digital ecosystem.
      </p>
      <nav v-if="canLogin" class="mt-6 flex gap-4">
        <Link
          v-if="$page.props.auth.user"
          :href="route('dashboard')"
          class="rounded-lg px-5 py-2 bg-green-600 text-white font-semibold shadow hover:bg-green-700 transition"
        >
          Dashboard
        </Link>
        <template v-else>
          <Link
            :href="route('login')"
            class="rounded-lg px-5 py-2 bg-green-600 text-white font-semibold shadow hover:bg-green-700 transition"
          >
            Log in
          </Link>
          <Link
            v-if="canRegister"
            :href="route('register')"
            class="rounded-lg px-5 py-2 bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition"
          >
            Register
          </Link>
        </template>
      </nav>
    </header>

    <!-- FEATURES -->
    <main class="flex-1 flex flex-col items-center justify-center py-10 px-4">
      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full max-w-6xl justify-items-center">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center border-t-4 border-green-400 w-full">
          <svg class="w-10 h-10 text-green-500 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v6a9 9 0 009 9 9 9 0 009-9V7"/></svg>
          <h2 class="text-lg font-bold text-green-700">Smart Land Search</h2>
          <p class="mt-2 text-gray-600">Find land listings with powerful filters, maps, and instant results tailored to your needs.</p>
        </div>
        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center border-t-4 border-blue-400 w-full">
          <svg class="w-10 h-10 text-blue-500 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          <h2 class="text-lg font-bold text-blue-700">Personal Land Agents</h2>
          <p class="mt-2 text-gray-600">Get dedicated assistance from expert agents—no brokers needed.</p>
        </div>
        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center border-t-4 border-yellow-400 w-full">
          <svg class="w-10 h-10 text-yellow-500 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          <h2 class="text-lg font-bold text-yellow-700">Save Time & User-Friendly</h2>
          <p class="mt-2 text-gray-600">Reduce time spent on independent searching with our easy-to-use, streamlined platform.</p>
        </div>
        <!-- Card 4 -->
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center border-t-4 border-red-400 w-full">
          <svg class="w-10 h-10 text-red-500 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"/></svg>
          <h2 class="text-lg font-bold text-red-700">Real-Time Communication</h2>
          <p class="mt-2 text-gray-600">Chat, inquire, and negotiate instantly with buyers, sellers, and agents.</p>
        </div>
      </div>

      <!-- ABOUT -->
      <section class="mt-16 max-w-3xl mx-auto text-center">
        <h3 class="text-2xl font-bold text-green-700 mb-2">Why LandSeek?</h3>
        <p class="text-gray-700 text-lg">LandSeek is designed to make land hunting easy, transparent, and accessible for everyone. Whether you're a buyer, seller, or agent, our platform empowers you to achieve your goals with confidence and convenience.</p>
      </section>
    </main>

    <!-- FOOTER -->
    <footer class="py-8 text-center text-sm text-gray-600 border-t border-gray-200 mt-8 bg-white">
      LandSeek &copy; {{ new Date().getFullYear() }} — Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
    </footer>
  </div>
</template>
