<script setup>
import NavMain from "@/components/NavMain.vue";
import NavUser from "@/components/NavUser.vue";
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/components/ui/sidebar";
import { Link } from "@inertiajs/vue3";
import {
    LayoutGrid,
    ShieldCheck,
    User,
    Banknote,
    BookOpenCheck,
    Bookmark,
    UserCog,
} from "lucide-vue-next";
import AppLogo from "./AppLogo.vue";
import usePermissions from "@/composables/usePermissions";

const { canAny } = usePermissions();

const dashboardNavItems = [
    {
        title: "Dashboard",
        href: "/dashboard",
        icon: LayoutGrid,
        permission: "dashboard",
    },
];

const manageUserNavItems = [
    {
        title: "Role",
        href: "/role",
        icon: ShieldCheck,
        permission: "role.index",
    },
    {
        title: "Pengguna",
        href: "/user",
        icon: User,
        permission: "user.index",
    },
];

const masterNavItems = [
    {
        title: "Akun Bank",
        href: "/bank-account",
        icon: Banknote,
        permission: "bank-account.index",
    },
    {
        title: "Program",
        href: "/program",
        icon: BookOpenCheck,
        permission: "program.index",
    },
    {
        title: "Periode",
        href: "/period",
        icon: Bookmark,
        permission: "period.index",
    },
];

const dataNavItems = [
    {
        title: "Pelatih",
        href: "/coach",
        icon: UserCog,
        permission: "coach.index",
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="dashboardNavItems" />
            <NavMain
                v-if="canAny('role.index', 'user.index')"
                group-label="Kelola Pengguna"
                :items="manageUserNavItems"
            />
            <NavMain
                v-if="
                    canAny(
                        'bank-account.index',
                        'program.index',
                        'period.index'
                    )
                "
                group-label="Master"
                :items="masterNavItems"
            />
            <NavMain
                v-if="canAny('coach.index')"
                group-label="Data"
                :items="dataNavItems"
            />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
