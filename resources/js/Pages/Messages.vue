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
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inbox</h2>
    </template>

    <div class="p-6">
      <div v-if="messages.length === 0">
        <p>No conversations yet.</p>
      </div>

      <ul v-else class="space-y-4">
        <li
          v-for="msg in messages"
          :key="msg.id"
          class="p-4 border rounded hover:bg-gray-50"
        >
          <div>
            <strong>Property:</strong> {{ msg.property?.title ?? 'N/A' }}<br />
            <strong>With:</strong>
            {{ msg.sender_id === authUser.id ? msg.receiver?.email : msg.sender?.email }}<br />
            <a
              :href="`/messages/property/${msg.property_id}`"
              class="text-blue-600 hover:underline inline-block mt-1"
            >
              View Conversation
            </a>
          </div>
        </li>
      </ul>
    </div>
  </AppLayout>
</template>
