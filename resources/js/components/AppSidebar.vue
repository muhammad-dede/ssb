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
    PersonStanding,
    FileDiff,
    SlidersHorizontal,
    Clock,
    FileBadge2,
} from "lucide-vue-next";
import AppLogo from "./AppLogo.vue";
import usePermissions from "@/composables/usePermissions";

const { canAny } = usePermissions();

const dashboardMenu = [
    {
        title: "Dashboard",
        href: "/dashboard",
        icon: LayoutGrid,
        permission: "dashboard",
    },
];

const adminManageUserMenu = [
    {
        title: "Role",
        href: "/admin/role",
        icon: ShieldCheck,
        permission: "admin.role.index",
    },
    {
        title: "Pengguna",
        href: "/admin/user",
        icon: User,
        permission: "admin.user.index",
    },
];

const adminMasterMenu = [
    {
        title: "Akun Bank",
        href: "/admin/bank-account",
        icon: Banknote,
        permission: "admin.bank-account.index",
    },
    {
        title: "Program",
        href: "/admin/program",
        icon: BookOpenCheck,
        permission: "admin.program.index",
    },
    {
        title: "Periode",
        href: "/admin/period",
        icon: Bookmark,
        permission: "admin.period.index",
    },
];

const adminDataMenu = [
    {
        title: "Pelatih",
        href: "/admin/coach",
        icon: UserCog,
        permission: "admin.coach.index",
    },
    {
        title: "Siswa",
        href: "/admin/student",
        icon: PersonStanding,
        permission: "admin.student.index",
    },
    {
        title: "Registrasi",
        href: "/admin/student-program",
        icon: FileDiff,
        permission: "admin.student-program.index",
    },
];

const adminActivityMenu = [
    {
        title: "Latihan",
        href: "/admin/training",
        icon: Clock,
        permission: "admin.training.index",
    },
    {
        title: "Pertandingan",
        href: "/admin/match-event",
        icon: SlidersHorizontal,
        permission: "admin.match-event.index",
    },
];

const adminReportMenu = [
    {
        title: "Raport Siswa",
        href: "/admin/report-student",
        icon: FileBadge2,
        permission: "admin.report-student.index",
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
            <NavMain :items="dashboardMenu" />
            <NavMain
                v-if="canAny('admin.role.index', 'admin.user.index')"
                group-label="Kelola Pengguna"
                :items="adminManageUserMenu"
            />
            <NavMain
                v-if="
                    canAny(
                        'admin.bank-account.index',
                        'admin.program.index',
                        'admin.period.index'
                    )
                "
                group-label="Master"
                :items="adminMasterMenu"
            />
            <NavMain
                v-if="
                    canAny(
                        'admin.coach.index',
                        'admin.student.index',
                        'admin.student-program.index'
                    )
                "
                group-label="Data"
                :items="adminDataMenu"
            />
            <NavMain
                v-if="canAny('admin.training.index', 'admin.match-event.index')"
                group-label="Aktifitas"
                :items="adminActivityMenu"
            />
            <NavMain
                v-if="canAny('admin.report-student.index')"
                group-label="Laporan"
                :items="adminReportMenu"
            />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
