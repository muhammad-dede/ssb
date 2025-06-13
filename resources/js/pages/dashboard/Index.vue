<script setup>
import { Head } from "@inertiajs/vue3";
import Heading from "@/components/Heading.vue";
import HeadingGroup from "@/components/HeadingGroup.vue";
import MainContent from "@/components/MainContent.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import usePermissions from "@/composables/usePermissions";
import Admin from "./Admin.vue";

const { hasAnyRole } = usePermissions();

const props = defineProps({
    admin: Object,
});

const breadcrumbs = [
    {
        title: "Dashboard",
        href: "/dashboard",
    },
];
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Dashboard"
                    description="Lihat dan kelola data pada dashboard"
                />
            </HeadingGroup>
            <template v-if="hasAnyRole('Super Admin', 'Admin')">
                <Admin :admin="admin" />
            </template>
        </MainContent>
    </AppLayout>
</template>
