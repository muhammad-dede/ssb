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
import usePermissions from "@/composables/usePermissions";

const { can, canAny } = usePermissions();

const props = defineProps({
    variants: Object,
    status_student_programs: Object,
    periods: Object,
    programs: Object,
    student_programs: Object,
    period_id_term: Number,
    program_code_term: Number,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const period_id = ref(props.period_id_term);
const program_code = ref(props.program_code_term);
const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);

const dataControl = () => {
    router.get(
        route("report-student.index"),
        {
            period_id: period_id.value,
            program_code: program_code.value,
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
watch([period_id, program_code, perPage, filter], () => {
    dataControl();
});

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Raport Siswa", href: "/report-student" },
];
</script>

<template>
    <Head title="Raport Siswa" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Data Raport Siswa"
                    description="Lihat dan kelola data raport siswa yang tersedia"
                />
            </HeadingGroup>
            <div
                class="flex flex-col lg:flex-row lg:justify-between items-center gap-4 mb-4"
            >
                <div class="grid w-full lg:grid-cols-3 lg:w-xl gap-4">
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
                    <Select v-model="program_code" name="program_code">
                        <SelectTrigger id="program_code" class="w-full">
                            <SelectValue placeholder="Pilih Program" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem
                                    v-for="(program, index) in programs"
                                    :key="index"
                                    :value="program.code"
                                >
                                    {{ program.name }}
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
                            <TableHead>Nama Siswa</TableHead>
                            <TableHead>Program</TableHead>
                            <TableHead>Rata-Rata Latihan</TableHead>
                            <TableHead>Rata-Rata Pertandingan</TableHead>
                            <TableHead>Total Nilai</TableHead>
                            <TableHead class="w-[10px]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="student_programs.data.length > 0">
                            <TableRow
                                v-for="(item, index) in student_programs.data"
                                :key="item.id"
                            >
                                <TableCell class="font-medium">
                                    {{ student_programs.from + index }}
                                </TableCell>
                                <TableCell>
                                    {{ item.student?.name }}
                                </TableCell>
                                <TableCell>
                                    {{ item.program?.name }}
                                </TableCell>
                                <TableCell>
                                    {{ item.report?.training?.total_score }}
                                </TableCell>
                                <TableCell>
                                    {{ item.report?.match_event?.total_score }}
                                </TableCell>
                                <TableCell class="font-bold">
                                    {{ item.report?.final_score }}
                                </TableCell>
                                <TableCell class="text-center">
                                    <DropdownMenu
                                        v-if="canAny('report-student.show')"
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
                                                v-if="
                                                    can('report-student.show')
                                                "
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'report-student.show',
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
            <PaginationLinks :paginator="student_programs" />
        </MainContent>
    </AppLayout>
</template>
