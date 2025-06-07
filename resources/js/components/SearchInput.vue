<script setup>
import { Input } from "@/components/ui/input";
import { Search } from "lucide-vue-next";

defineProps({
    name: {
        type: String,
        default: "search",
    },
    modelValue: {
        type: String,
        default: "",
    },
    placeholder: {
        type: String,
        default: "Cari...",
    },
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="relative w-full max-w-sm items-center">
        <Input
            :id="name"
            type="search"
            :placeholder="placeholder"
            class="pl-8"
            autocomplete="off"
            :name="name"
            :model-value="modelValue"
            @update:model-value="emit('update:modelValue', $event)"
        />
        <span
            class="absolute start-0 inset-y-0 flex items-center justify-center px-2"
        >
            <Search class="size-4 text-muted-foreground" />
        </span>
    </div>
</template>
