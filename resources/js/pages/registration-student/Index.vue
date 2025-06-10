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
import { Badge } from "@/components/ui/badge/index";
import SearchInput from "@/components/SearchInput.vue";
import FilterControl from "@/components/FilterControl.vue";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import usePermissions from "@/composables/usePermissions";

const { can, canAny } = usePermissions();

const props = defineProps({
    periods: Object,
    status_student_programs: Object,
    status_billings: Object,
    student_programs: Object,
    period_id_terms: Number,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Registrasi", href: "/registration-student" },
];

const period_id = ref(props.period_id_terms);
const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);

const dataControl = () => {
    router.get(
        route("registration-student.index"),
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
    const found = props.status_student_programs?.find(
        (item) => item.value === status
    );
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
</script>

<template>
    <Head title="Registrasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Data Registrasi"
                    description="Lihat dan kelola data registrasi siswa yang tersedia"
                />
                <Link
                    v-if="can('registration-student.create')"
                    :href="route('registration-student.create')"
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
                            <TableHead>Nama Siswa</TableHead>
                            <TableHead>Periode</TableHead>
                            <TableHead>Program</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Keterangan</TableHead>
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
                                    {{ item.period?.name }}
                                </TableCell>
                                <TableCell>
                                    {{ item.program?.name }}
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
                                <TableCell> Keterangan </TableCell>
                                <TableCell class="text-center">
                                    <DropdownMenu
                                        v-if="
                                            canAny(
                                                'registration-student.edit',
                                                'registration-student.show'
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
                                                v-if="
                                                    can(
                                                        'registration-student.show'
                                                    )
                                                "
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'registration-student.show',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                asChild
                                                v-if="
                                                    can(
                                                        'registration-student.edit'
                                                    )
                                                "
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'registration-student.edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Ubah
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
                                    <strong> Tidak ada data </strong>
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
