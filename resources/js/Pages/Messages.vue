<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

defineProps(['messages']);

const page = usePage();
const authUser = page.props.auth.user;
</script>

<template>
  <AppLayout title="Messages">
    <!-- Header -->
    <template #header>
      <div class="flex items-center gap-3 py-5 px-4 bg-gradient-to-r from-green-50 via-white to-blue-50 rounded-t-2xl shadow-sm border-b border-gray-200">
        <div class="bg-green-600 p-2 rounded-full shadow">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
        </div>
        <h2 class="font-extrabold text-2xl text-green-700 tracking-tight">Inbox</h2>
      </div>
    </template>

    <!-- Main Content -->
    <div class="py-10 px-4 bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-screen">
      <div class="max-w-4xl mx-auto">
        
        <!-- Empty State -->
        <div v-if="messages.length === 0" class="flex flex-col items-center justify-center py-16 bg-white rounded-2xl shadow-lg border border-green-200">
          <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
          <p class="text-gray-500 text-lg">No conversations yet.</p>
        </div>

        <!-- Conversations List -->
        <ul v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <li
            v-for="msg in messages"
            :key="msg.id"
            class="bg-white rounded-2xl shadow-lg border border-green-100 p-5 hover:shadow-xl hover:scale-[1.02] transition transform flex flex-col justify-between"
          >
            <div class="mb-4">
              <div class="text-sm text-gray-500 mb-2">
                <span class="font-semibold text-green-700">Property:</span> {{ msg.property?.title ?? 'N/A' }}
              </div>
              <div class="text-sm text-gray-500">
                <span class="font-semibold text-green-700">With:</span>
                {{ msg.sender_id === authUser.id ? msg.receiver?.email : msg.sender?.email }}
              </div>
            </div>
            <a
              :href="`/messages/property/${msg.property_id}`"
              class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow transition text-center"
            >
              View Conversation
            </a>
          </li>
        </ul>

      </div>
    </div>
  </AppLayout>
</template>
