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
    periods: Object,
    status_trainings: Object,
    variants: Object,
    trainings: Object,
    period_id_terms: Number,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const period_id = ref(props.period_id_terms);
const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);

const dataControl = () => {
    router.get(
        route("training.index"),
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
    const found = props.status_trainings?.find((item) => item.value === status);
    return found?.label?.toUpperCase() ?? "-";
};
const getStatusVariant = (status) => {
    if (!status) return "outline";
    const found = props.variants?.find((item) => item.value === status);
    return found?.label ?? "outline";
};

const trainingToStatus = ref(null);
const confirmStatus = (training) => {
    trainingToStatus.value = training;
};
const changeStatus = () => {
    if (!trainingToStatus.value) return;
    const trainingId = trainingToStatus.value.id;
    router.post(route("training.status", trainingId), {
        preserveScroll: true,
    });
    trainingToStatus.value = null;
};

const trainingToDelete = ref(null);
const confirmDelete = (training) => {
    trainingToDelete.value = training;
};
const destroy = () => {
    if (!trainingToDelete.value) return;
    const trainingId = trainingToDelete.value.id;
    router.delete(route("training.destroy", trainingId), {
        preserveScroll: true,
        onFinish: () => {
            trainingToDelete.value = null;
        },
    });
};

const setTrainingTime = (training) => {
    if (!training?.training_date) return "-";
    const date = new Date(training?.training_date).toLocaleDateString("id-ID", {
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
    const startTime = formatTime(training?.start_time);
    const endTime = formatTime(training?.end_time);
    return `${date}, ${startTime} - ${endTime}`;
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Latihan", href: "/training" },
];
</script>

<template>
    <Head title="Latihan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Data Latihan"
                    description="Lihat dan kelola data latihan siswa yang tersedia"
                />
                <Link
                    v-if="can('training.create')"
                    :href="route('training.create')"
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
                        <template v-if="trainings.data.length > 0">
                            <TableRow
                                v-for="(item, index) in trainings.data"
                                :key="item.id"
                            >
                                <TableCell class="font-medium">
                                    {{ trainings.from + index }}
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
                                    {{ setTrainingTime(item) }}
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
                                                'training.edit',
                                                'training.show',
                                                'training.delete'
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
                                                v-if="can('training.edit')"
                                                @select="
                                                    () => confirmStatus(item)
                                                "
                                            >
                                                Ubah Status
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                asChild
                                                v-if="can('training.show')"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'training.show',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                asChild
                                                v-if="can('training.edit')"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'training.edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Ubah
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                v-if="can('training.delete')"
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
                                    <strong> Tidak ada data </strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
            <PaginationLinks :paginator="trainings" />
        </MainContent>
    </AppLayout>
    <AlertDialog :open="!!trainingToDelete">
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
                <AlertDialogCancel @click="trainingToDelete = null">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="destroy">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
    <AlertDialog :open="!!trainingToStatus">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>
                    Apakah Anda benar-benar yakin?
                </AlertDialogTitle>
                <AlertDialogDescription>
                    Status Pelatih akan diubah.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="trainingToStatus = null">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="changeStatus"
                    >Ubah Status</AlertDialogAction
                >
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
