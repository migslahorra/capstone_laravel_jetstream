<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
  messages: Array,
  property_id: [Number, String],
  presetMessage: String,
  propertyOwnerId: [Number, null],
});

const propertyId = Number(props.property_id);
const newMessage = ref(props.presetMessage || '');
const page = usePage();
const authUser = page.props.auth.user;

const otherUserId = computed(() => {
  if (props.messages.length > 0) {
    const first = props.messages[0];
    return first.sender_id === authUser.id ? first.receiver_id : first.sender_id;
  }

  const ownerId = props.propertyOwnerId;
  if (ownerId && ownerId !== authUser.id) {
    return ownerId;
  }

  return null;
});

function sendMessage() {
  if (!newMessage.value.trim()) return;

  if (!otherUserId.value) {
    alert('Cannot determine the recipient.');
    return;
  }

  router.post('/messages', {
    receiver_id: otherUserId.value,
    property_id: propertyId,
    content: newMessage.value,
  }, {
    onSuccess: () => {
      newMessage.value = '';
    }
  });
}
</script>

<template>
  <AppLayout title="Conversation">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Conversation</h2>
    </template>

    <div class="p-6 space-y-4">
      <div v-if="messages.length">
        <div
          v-for="msg in messages"
          :key="msg.id"
          class="border p-3 rounded bg-gray-50"
        >
          <p class="text-sm text-gray-500">{{ msg.sender?.email }}:</p>
          <p class="text-gray-800">{{ msg.message }}</p>
        </div>
      </div>
      <div v-else class="text-gray-500">
        <p>This is the start of your conversation.</p>
      </div>

      <div class="mt-6">
        <textarea
          v-model="newMessage"
          placeholder="Type your message..."
          class="w-full border p-3 rounded"
        ></textarea>
        <button
          @click="sendMessage"
          class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Send
        </button>
      </div>

      <div class="mt-4">
        <a href="/messages" class="text-blue-600 hover:underline">← Back to Inbox</a>
      </div>
    </div>
  </AppLayout>
</template>
