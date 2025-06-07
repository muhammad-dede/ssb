<!-- src/components/Lightbox.vue -->
<script setup>
import { X } from "lucide-vue-next";

defineProps({
    show: Boolean,
    imageUrl: String,
});
const emit = defineEmits(["close"]);
</script>

<template>
    <teleport to="body">
        <div
            v-if="show"
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center"
        >
            <button
                class="absolute top-4 right-4 bg-white border rounded-full p-2 shadow hover:bg-gray-100 z-50"
                @click="$emit('close')"
                aria-label="Close"
            >
                <X />
            </button>
            <img
                :src="imageUrl"
                alt="Preview Besar"
                class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-lg"
            />
        </div>
    </teleport>
</template>
