<script setup>
defineProps({
    background: {
        type: Boolean,
        default: false,
    },
    reverse: {
        type: Boolean,
        default: false,
    },
    label: String,
    value: String,
    icon: {
        type: [Object, Function],
        required: true,
    },
});
</script>

<template>
    <div
        v-if="!background"
        :class="[
            // 'flex lg:min-w-xs lg:max-w-sm py-4',
            'flex py-4',
            reverse ? 'flex-col-reverse' : 'flex-col',
        ]"
    >
        <span
            v-if="value"
            :class="['font-bold', reverse ? 'text-sm' : 'text-base']"
        >
            {{ value }}
        </span>
        <div
            v-if="label"
            class="flex items-center font-semibold gap-1 text-gray-600"
        >
            <component :is="icon" class="size-3" />
            <span class="text-sm">
                {{ label }}
            </span>
        </div>
    </div>
    <div v-else class="flex gap-2 items-start py-4">
        <div
            class="rounded-xl flex items-center justify-center size-10 shrink-0"
            :class="[`bg-gray-100`, 'p-2']"
        >
            <component :is="icon" class="size-4" :class="`text-gray-600`" />
        </div>
        <div class="grid gap-0.5">
            <p class="text-xs font-semibold text-gray-500">
                {{ label }}
            </p>
            <span :class="`text-sm font-bold text-gray-600`">
                {{ value }}
            </span>
        </div>
    </div>
</template>
