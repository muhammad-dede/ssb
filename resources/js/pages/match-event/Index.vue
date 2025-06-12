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
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table/index";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
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
import { Badge } from "@/components/ui/badge/index";
import SearchInput from "@/components/SearchInput.vue";
import FilterControl from "@/components/FilterControl.vue";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import usePermissions from "@/composables/usePermissions";

const { can, canAny } = usePermissions();

const props = defineProps({
    variants: Object,
    status_match_events: Object,
    periods: Object,
    match_events: Object,
    period_id_term: Number,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const period_id = ref(props.period_id_term);
const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);
const matchEventToDelete = ref(null);

const dataControl = () => {
    router.get(
        route("match-event.index"),
        {
            period_id: period_id.value,
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

watch([period_id, perPage, filter], () => {
    dataControl();
});

const getStatusLabel = (status) => {
    if (!status) return "-";
    const found = props.status_match_events?.find(
        (item) => item.value === status
    );
    return found?.label?.toUpperCase() ?? "-";
};

const getStatusVariant = (status) => {
    if (!status) return "outline";
    const found = props.variants?.find((item) => item.value === status);
    return found?.label ?? "outline";
};

const confirmDelete = (matchEvent) => {
    matchEventToDelete.value = matchEvent;
};

const destroy = () => {
    if (!matchEventToDelete.value) return;
    const matchEventId = matchEventToDelete.value.id;
    router.delete(route("match-event.destroy", matchEventId), {
        preserveScroll: true,
        onFinish: () => {
            matchEventToDelete.value = null;
        },
    });
};

const setTime = (matchEvent) => {
    if (!matchEvent?.match_date) return "-";
    const date = new Date(matchEvent?.match_date).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
    const formatTime = (time) => {
        if (!time) return "-";
        const [hours, minutes] = time.split(":");
        const dateObj = new Date();
        dateObj.setHours(hours, minutes);
        return dateObj.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
        });
    };
    const startTime = formatTime(matchEvent?.start_time);
    const endTime = formatTime(matchEvent?.end_time);
    return `${date}, ${startTime} - ${endTime}`;
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Pertandingan", href: "/match-event" },
];
</script>

<template>
    <Head title="Pertandingan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Data Pertandingan"
                    description="Lihat dan kelola data pertandingan yang tersedia"
                />
                <Link
                    v-if="can('match-event.create')"
                    :href="route('match-event.create')"
                    :class="buttonVariants({ variant: 'default' })"
                >
                    <SquarePlus class="w-4 h-4" />Tambah
                </Link>
            </HeadingGroup>
            <div
                class="flex flex-col lg:flex-row lg:justify-between items-center gap-4 mb-4"
            >
                <div class="grid w-full lg:grid-cols-2 lg:w-xl gap-4">
                    <Select v-model="period_id" name="period_id">
                        <SelectTrigger id="period_id" class="w-full">
                            <SelectValue placeholder="Pilih Periode" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem
                                    v-for="(period, index) in periods"
                                    :key="index"
                                    :value="period.id"
                                >
                                    {{ period.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <SearchInput v-model="search" class="max-w-full" />
                </div>
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
                            <TableHead>Program</TableHead>
                            <TableHead>Periode</TableHead>
                            <TableHead>Pelatih</TableHead>
                            <TableHead>Waktu</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="w-[10px]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="match_events.data.length > 0">
                            <TableRow
                                v-for="(item, index) in match_events.data"
                                :key="item.id"
                            >
                                <TableCell class="font-medium">
                                    {{ match_events.from + index }}
                                </TableCell>
                                <TableCell>
                                    {{ item.program?.name ?? "-" }}
                                </TableCell>
                                <TableCell>
                                    {{ item.period?.name ?? "-" }}
                                </TableCell>
                                <TableCell>
                                    {{ item.coach?.name ?? "-" }}
                                </TableCell>
                                <TableCell>
                                    {{ setTime(item) }}
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
                                                'match-event.edit',
                                                'match-event.show',
                                                'match-event.delete'
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
                                                asChild
                                                v-if="can('match-event.show')"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'match-event.show',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                asChild
                                                v-if="can('match-event.edit')"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'match-event.edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Ubah
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                v-if="can('match-event.delete')"
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
                                <TableCell colspan="7" class="text-center py-6">
                                    <strong>Tidak ada data</strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
            <PaginationLinks :paginator="match_events" />
        </MainContent>
    </AppLayout>

    <AlertDialog :open="!!matchEventToDelete">
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
                <AlertDialogCancel @click="matchEventToDelete = null">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="destroy">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
