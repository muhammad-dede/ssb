<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import AppSidebar from "@/components/AppSidebar.vue";
import AppContent from "@/components/AppContent.vue";
import { SidebarProvider } from "@/components/ui/sidebar/index";
import AppSidebarHeader from "@/components/AppSidebarHeader.vue";
import FlashMessage from "@/components/FlashMessage.vue";

const props = defineProps({
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
});

const isOpen = usePage().props.sidebarOpen;

// Computed untuk flash message
const flashMessage = computed(() => {
    const flash = usePage().props.flash;
    return {
        success: flash.success,
        failed: flash.failed,
        hasMessage: !!(flash.success || flash.failed),
    };
});

// Key untuk force re-render ketika ada flash message baru
const flashKey = computed(() => {
    const flash = flashMessage.value;
    return flash.hasMessage
        ? `${flash.success || flash.failed}-${Date.now()}`
        : null;
});
</script>

<template>
    <SidebarProvider :default-open="isOpen">
        <AppSidebar />
        <AppContent>
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </SidebarProvider>

    <FlashMessage
        v-if="flashMessage.hasMessage"
        :key="flashKey"
        :type="flashMessage.success ? 'success' : 'failed'"
        :message="flashMessage.success || flashMessage.failed"
    />
</template>
