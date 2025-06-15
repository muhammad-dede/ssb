<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import AppLayout from "@/layouts/AppLayout.vue";
import MainContent from "@/components/MainContent.vue";
import PaginationLinks from "@/components/PaginationLinks.vue";
import { Button } from "@/components/ui/button/index";
import { MoreHorizontal } from "lucide-vue-next";
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
import SearchInput from "@/components/SearchInput.vue";
import FilterControl from "@/components/FilterControl.vue";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";

const props = defineProps({
    periods: Object,
    trainings: Object,
    period_id_term: Number,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const period_id = ref(props.period_id_term);
const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);

const dataControl = () => {
    router.get(
        route("student.training.index"),
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
    { title: "Latihan", href: "/student/training" },
];
</script>

<template>
    <Head title="Latihan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Jadwal Latihan Latihan"
                    description="Lihat jadwal latihan yang tersedia"
                />
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
                                <TableCell class="text-center">
                                    <DropdownMenu>
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
                                            <DropdownMenuItem asChild>
                                                <Link
                                                    :href="
                                                        route(
                                                            'student.training.show',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Detail
                                                </Link>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow>
                                <TableCell colspan="6" class="text-center py-6">
                                    <strong>Tidak ada data</strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
            <PaginationLinks :paginator="trainings" />
        </MainContent>
    </AppLayout>
</template>
