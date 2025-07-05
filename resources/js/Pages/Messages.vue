<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

defineProps(['messages']);

const page = usePage();
const authUser = page.props.auth.user;
</script>

<template>
  <AppLayout title="Messages">
    <template #header>
      <div class="flex items-center gap-3 py-2">
        <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"/><circle cx="12" cy="12" r="3"/></svg>
        <h2 class="font-extrabold text-2xl text-green-700 tracking-tight">Inbox</h2>
      </div>
    </template>

    <div class="p-6 bg-gradient-to-br from-green-100 via-white to-blue-100 min-h-[60vh] rounded-xl shadow">
      <div v-if="messages.length === 0" class="flex flex-col items-center justify-center py-16">
        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"/><circle cx="12" cy="12" r="3"/></svg>
        <p class="text-gray-500 text-lg">No conversations yet.</p>
      </div>

      <ul v-else class="space-y-4">
        <li
          v-for="msg in messages"
          :key="msg.id"
          class="p-5 bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center md:justify-between border-t-4 border-green-200 hover:border-green-400 transition"
        >
          <div class="mb-2 md:mb-0">
            <div class="text-sm text-gray-500 mb-1"><span class="font-semibold text-green-700">Property:</span> {{ msg.property?.title ?? 'N/A' }}</div>
            <div class="text-sm text-gray-500"><span class="font-semibold text-green-700">With:</span> {{ msg.sender_id === authUser.id ? msg.receiver?.email : msg.sender?.email }}</div>
          </div>
          <a
            :href="`/messages/property/${msg.property_id}`"
            class="inline-block mt-2 md:mt-0 px-5 py-2 bg-green-600 text-white rounded-lg font-semibold shadow hover:bg-green-700 transition"
          >
            View Conversation
          </a>
        </li>
      </ul>
    </div>
  </AppLayout>
</template>
