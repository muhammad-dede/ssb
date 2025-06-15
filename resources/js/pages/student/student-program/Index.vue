<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { debounce } from "lodash";
import AppLayout from "@/layouts/AppLayout.vue";
import MainContent from "@/components/MainContent.vue";
import PaginationLinks from "@/components/PaginationLinks.vue";
import { Button, buttonVariants } from "@/components/ui/button/index";
import { MoreHorizontal, Bell, TriangleAlert } from "lucide-vue-next";
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
    Alert,
    AlertDescription,
    AlertTitle,
} from "@/components/ui/alert/index";
import { Badge } from "@/components/ui/badge/index";
import SearchInput from "@/components/SearchInput.vue";
import FilterControl from "@/components/FilterControl.vue";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";

const props = defineProps({
    variants: Object,
    status_payments: Object,
    status_billings: Object,
    status_student_programs: Object,
    period_active: Object,
    student_programs: Object,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);
const studentProgramToDelete = ref(null);

const dataControl = () => {
    router.get(
        route("student.student-program.index"),
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

const shouldRegisterNow = computed(() => {
    const programs = props.student_programs.data || [];
    if (programs.length === 0) {
        return true;
    }
    const hasActivePeriod = programs.some((program) => {
        return program.period_id === props.period_active?.id;
    });
    return !hasActivePeriod;
});

const getStatusLabel = (student_program) => {
    if (!student_program) return "-";
    const isActive = student_program.status === "ACTIVE";
    const source = isActive
        ? props.status_student_programs
        : student_program.billing?.status !== "PAID"
        ? props.status_billings
        : props.status_payments;
    const status = isActive
        ? student_program.status
        : student_program.billing?.status !== "PAID"
        ? student_program.billing?.status
        : student_program.billing?.payment?.status;
    const found = source?.find((item) => item.value === status);
    return found?.label?.toUpperCase() ?? "-";
};

const getStatusVariant = (student_program) => {
    if (!student_program) return "outline";
    const isActive = student_program.status === "ACTIVE";
    const status = isActive
        ? student_program.status
        : student_program.billing?.status !== "PAID"
        ? student_program.billing?.status
        : student_program.billing?.payment?.status;
    const found = props.variants?.find((item) => item.value === status);
    return found?.label ?? "outline";
};

const confirmDelete = (studentProgram) => {
    studentProgramToDelete.value = studentProgram;
};

const destroy = () => {
    if (!studentProgramToDelete.value) return;
    const studentProgramId = studentProgramToDelete.value.id;
    router.delete(route("student.student-program.destroy", studentProgramId), {
        preserveScroll: true,
        onFinish: () => {
            studentProgramToDelete.value = null;
        },
    });
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Registrasi", href: "/student/student-program" },
];
</script>

<template>
    <Head title="Registrasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Registrasi"
                    description="Lihat dan kelola data registrasi yang tersedia"
                />
                <Link
                    v-if="shouldRegisterNow"
                    :href="route('student.student-program.create')"
                    :class="buttonVariants({ variant: 'default' })"
                >
                    Registrasi
                </Link>
            </HeadingGroup>
            <Alert class="mb-4">
                <Bell class="h-4 w-4" />
                <AlertTitle>Periode Saat Ini: </AlertTitle>
                <AlertDescription>
                    {{ period_active?.name ?? "Tidak ada periode aktif" }}
                </AlertDescription>
            </Alert>
            <Alert
                v-if="shouldRegisterNow"
                variant="destructive"
                class="mb-4 border-red-300"
            >
                <TriangleAlert class="h-4 w-4" />
                <AlertTitle>Belum Terdaftar</AlertTitle>
                <AlertDescription>
                    Anda belum terdaftar. Silahkan melakukan registrasi terlebih
                    dahulu.
                </AlertDescription>
            </Alert>
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
                            <TableHead>Periode</TableHead>
                            <TableHead>Program</TableHead>
                            <TableHead>Status</TableHead>
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
                                    {{ item.period?.name }}
                                </TableCell>
                                <TableCell>
                                    {{ item.program?.name }}
                                </TableCell>
                                <TableCell>
                                    <Badge :variant="getStatusVariant(item)">
                                        {{ getStatusLabel(item) }}
                                    </Badge>
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
                                                            'student.student-program.show',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem asChild>
                                                <Link
                                                    :href="
                                                        route(
                                                            'student.student-program.edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Ubah
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                v-if="item.status !== 'ACTIVE'"
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
                                <TableCell colspan="5" class="text-center py-6">
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
    <AlertDialog :open="!!studentProgramToDelete">
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
                <AlertDialogCancel @click="studentProgramToDelete = null">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="destroy">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
