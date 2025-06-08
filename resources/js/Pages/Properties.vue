<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Land Properties">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight justify-center">
                    Uploaded Land Properties
                </h2>

                <!-- Upload Property button visible only to Sellers -->
                <template v-if="$page.props.auth.user && $page.props.auth.user.role === 'Seller'">
                    <a href="/upload" class="ml-4" style="border: 1px solid lightgrey; padding: 10px; padding-left: 15px; padding-right: 15px;">
                        <i class="fa-solid fa-upload" style="margin-right: 5px;"></i>Upload Property
                    </a>
                </template>
            </div>
        </template>
    </AppLayout>
</template>
