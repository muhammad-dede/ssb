<script setup>
import { Head } from "@inertiajs/vue3";

import AppearanceTabs from "@/components/AppearanceTabs.vue";
import HeadingSmall from "@/components/HeadingSmall.vue";

import AppLayout from "@/layouts/AppLayout.vue";
import SettingsLayout from "@/layouts/settings/Layout.vue";

const breadcrumbItems = [
    {
        title: "Appearance settings",
        href: "/settings/appearance",
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Appearance settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Appearance settings"
                    description="Update your account's appearance settings"
                />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
