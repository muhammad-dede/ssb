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
    student_match_events: Object,
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
        route("student.match-event.index"),
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

const setDate = (matchEvent) => {
    if (!matchEvent?.match_date) return "-";
    const date = new Date(matchEvent?.match_date).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
    return `${date}`;
};

const setTime = (matchEvent) => {
    if (!matchEvent?.match_date) return "-";
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
    return `${startTime} - ${endTime}`;
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Pertandingan", href: "/student/match-event" },
];
</script>

<template>
    <Head title="Pertandingan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Jadwal Pertandingan"
                    description="Lihat jadwal pertandingan yang tersedia"
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
                            <TableHead>Tanggal</TableHead>
                            <TableHead>Waktu</TableHead>
                            <TableHead>Pelatih</TableHead>
                            <TableHead>Lawan</TableHead>
                            <TableHead>Skor</TableHead>
                            <TableHead>Kehadiran</TableHead>
                            <TableHead class="w-[10px]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="student_match_events.data.length > 0">
                            <TableRow
                                v-for="(
                                    item, index
                                ) in student_match_events.data"
                                :key="item.id"
                            >
                                <TableCell class="font-medium">
                                    {{ student_match_events.from + index }}
                                </TableCell>
                                <TableCell>
                                    {{ setDate(item.match_event) }}
                                </TableCell>
                                <TableCell>
                                    {{ setTime(item.match_event) }}
                                </TableCell>
                                <TableCell>
                                    {{ item.match_event?.coach?.name ?? "-" }}
                                </TableCell>
                                <TableCell>
                                    {{ item.match_event?.opponent ?? "-" }}
                                </TableCell>
                                <TableCell>
                                    {{ item.match_event?.our_score ?? "0" }} -
                                    {{
                                        item.match_event?.opponent_score ?? "0"
                                    }}
                                </TableCell>
                                <TableCell>
                                    {{ item.attendance_label ?? "-" }}
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
                                                            'student.match-event.show',
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
                                <TableCell colspan="7" class="text-center py-6">
                                    <strong>Tidak ada data</strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
            <PaginationLinks :paginator="student_match_events" />
        </MainContent>
    </AppLayout>
</template>
