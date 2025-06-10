<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import AppLayout from "@/layouts/AppLayout.vue";
import MainContent from "@/components/MainContent.vue";
import PaginationLinks from "@/components/PaginationLinks.vue";
import { Button, buttonVariants } from "@/components/ui/button/index";
import { SquarePlus, MoreHorizontal } from "lucide-vue-next";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table/index";
import { Badge } from "@/components/ui/badge/index";
import SearchInput from "@/components/SearchInput.vue";
import FilterControl from "@/components/FilterControl.vue";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import usePermissions from "@/composables/usePermissions";

const { can, canAny } = usePermissions();

const props = defineProps({
    status_periods: Object,
    periods: Object,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Periode", href: "/period" },
];

const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);
const periodToDelete = ref(null);
const periodToStatus = ref(null);

const dataControl = () => {
    router.get(
        route("period.index"),
        {
            search: search.value,
            per_page: perPage.value,
            filter: filter.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};
watch(
    search,
    debounce(() => {
        dataControl();
    }, 1000)
);
watch([perPage, filter], () => {
    dataControl();
});

const confirmDelete = (period) => {
    periodToDelete.value = period;
};
const destroy = () => {
    if (!periodToDelete.value) return;
    const periodId = periodToDelete.value.id;
    router.delete(route("period.destroy", periodId), {
        preserveScroll: true,
        onFinish: () => {
            periodToDelete.value = null;
        },
    });
};

const confirmStatus = (period) => {
    periodToStatus.value = period;
};
const changeStatus = () => {
    if (!periodToStatus.value) return;
    const periodId = periodToStatus.value.id;
    router.post(route("period.status", periodId), {
        preserveScroll: true,
    });
    periodToStatus.value = null;
};

const getStatusLabel = (status) => {
    if (!status) return "-";
    const found = props.status_periods?.find((item) => item.value === status);
    return found?.label?.toUpperCase() ?? "-";
};
const getStatusVariant = (status) => {
    if (!status) return "outline";
    switch (status) {
        case "ACTIVE":
            return "default";
        case "INACTIVE":
            return "destructive";
        default:
            return "outline";
    }
};
const getStatusChangeLabel = (status) => {
    if (!status) return "Aktifkan";
    switch (status) {
        case "ACTIVE":
            return "Nonaktifkan";
        case "INACTIVE":
            return "Aktifkan";
        default:
            return "Aktifkan";
    }
};

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};
</script>

<template>
    <Head title="Periode" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Data Periode"
                    description="Lihat dan kelola data periode yang tersedia"
                />
                <Link
                    v-if="can('period.create')"
                    :href="route('period.create')"
                    :class="buttonVariants({ variant: 'default' })"
                >
                    <SquarePlus class="w-4 h-4" />Tambah
                </Link>
            </HeadingGroup>
            <div class="flex justify-between items-center gap-4 mb-4">
                <SearchInput v-model="search" />
                <FilterControl
                    :per-page="perPage"
                    :filter="filter"
                    @update:per-page="perPage = $event"
                    @update:filter="filter = $event"
                />
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[10px]">No</TableHead>
                            <TableHead>Nama Periode</TableHead>
                            <TableHead>Tanggal Mulai</TableHead>
                            <TableHead>Tanggal Akhir</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="w-[10px]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="periods.data.length > 0">
                            <TableRow
                                v-for="(item, index) in periods.data"
                                :key="item.id"
                            >
                                <TableCell class="font-medium">
                                    {{ periods.from + index }}
                                </TableCell>
                                <TableCell>
                                    {{ item.name }}
                                </TableCell>
                                <TableCell>
                                    {{ dateFormat(item.start_date) }}
                                </TableCell>
                                <TableCell>
                                    {{ dateFormat(item.end_date) }}
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            getStatusVariant(item?.status)
                                        "
                                    >
                                        {{ getStatusLabel(item?.status) }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-center">
                                    <DropdownMenu
                                        v-if="
                                            canAny(
                                                'period.edit',
                                                'period.delete'
                                            )
                                        "
                                    >
                                        <DropdownMenuTrigger as-child>
                                            <Button
                                                variant="outline"
                                                class="w-8 h-8 p-0"
                                            >
                                                <MoreHorizontal
                                                    class="w-4 h-4"
                                                />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuLabel>
                                                Aksi
                                            </DropdownMenuLabel>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem
                                                v-if="can('period.edit')"
                                                @select="
                                                    () => confirmStatus(item)
                                                "
                                            >
                                                {{
                                                    getStatusChangeLabel(
                                                        item?.status
                                                    )
                                                }}
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                asChild
                                                v-if="can('period.edit')"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'period.edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Ubah
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                v-if="can('period.delete')"
                                                @select="
                                                    () => confirmDelete(item)
                                                "
                                            >
                                                Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow>
                                <TableCell colspan="6" class="text-center py-6">
                                    <strong> Tidak ada data </strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
            <PaginationLinks :paginator="periods" />
        </MainContent>
    </AppLayout>
    <AlertDialog :open="!!periodToDelete">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>
                    Apakah Anda benar-benar yakin?
                </AlertDialogTitle>
                <AlertDialogDescription>
                    Tindakan ini tidak dapat dibatalkan. Ini akan secara
                    permanen menghapus data terkait dari server kami.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="periodToDelete = null">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="destroy">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
    <AlertDialog :open="!!periodToStatus">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>
                    Apakah Anda benar-benar yakin?
                </AlertDialogTitle>
                <AlertDialogDescription>
                    Status Periode akan di-{{
                        getStatusChangeLabel(periodToStatus?.status ?? null)
                    }}.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="periodToStatus = null">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="changeStatus">{{
                    getStatusChangeLabel(periodToStatus?.status ?? null)
                }}</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
