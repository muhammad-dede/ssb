<script setup>
import { Link } from "@inertiajs/vue3";
import { buttonVariants } from "./ui/button/index";

defineProps({
    paginator: {
        type: Object,
        required: true,
    },
});

const makeLabel = (label) => {
    if (label.includes("Previous") || label.includes("Sebelumnya")) {
        return "Previous";
    } else if (label.includes("Next") || label.includes("Selanjutnya")) {
        return "Next";
    } else {
        return label;
    }
};
</script>

<template>
    <div
        class="flex flex-col items-center space-y-4 space-x-2 py-4 lg:flex-row lg:space-y-0"
    >
        <div class="flex-1 text-sm text-muted-foreground">
            Showing {{ paginator.from ?? "0" }} to {{ paginator.to ?? "0" }} of
            {{ paginator.total ?? "0" }} results
        </div>
        <div class="space-x-2">
            <Link
                v-for="(link, index) in paginator.links"
                :key="index"
                :href="link.url ?? ''"
                preserve-scroll
                :class="[
                    buttonVariants({
                        variant: link.active ? 'default' : 'outline',
                        size: 'sm',
                    }),
                    !link.url ? 'pointer-events-none opacity-50' : '',
                ]"
            >
                <span>{{ makeLabel(link.label) }}</span>
            </Link>
        </div>
    </div>
</template>
