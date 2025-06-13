<script setup>
import { Head } from "@inertiajs/vue3";

import AppearanceTabs from "@/components/AppearanceTabs.vue";
import HeadingSmall from "@/components/HeadingSmall.vue";

import AppLayout from "@/layouts/AppLayout.vue";
import SettingsLayout from "@/layouts/settings/Layout.vue";

const breadcrumbItems = [
    {
        title: "Pengaturan Tampilan",
        href: "/settings/appearance",
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Pengaturan Tampilan" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Pengaturan Tampilan"
                    description="Perbarui pengaturan tampilan akun Anda"
                />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
