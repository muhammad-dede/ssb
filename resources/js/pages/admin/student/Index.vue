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
    variants: Object,
    status_student_programs: Object,
    genders: Object,
    students: Object,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);
const studentToDelete = ref(null);

const dataControl = () => {
    router.get(
        route("admin.student.index"),
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

const confirmDelete = (student) => {
    studentToDelete.value = student;
};

const destroy = () => {
    if (!studentToDelete.value) return;
    const studentId = studentToDelete.value.id;
    router.delete(route("admin.student.destroy", studentId), {
        preserveScroll: true,
        onFinish: () => {
            studentToDelete.value = null;
        },
    });
};

const getGenderLabel = (gender) => {
    if (!gender) return "-";
    const found = props.genders?.find((item) => item.value === gender);
    return found?.label ?? "-";
};

const getStatusLabel = (status) => {
    if (!status) return "Belum Terdaftar Diperiode Ini";
    const found = props.status_student_programs?.find(
        (item) => item.value === status
    );
    return found?.label?.toUpperCase() ?? "Belum Terdaftar Diperiode Ini";
};

const getStatusVariant = (status) => {
    if (!status) return "outline";
    const found = props.variants?.find((item) => item.value === status);
    return found?.label ?? "outline";
};

const getAge = (birthDate) => {
    if (!birthDate) return "-";
    const today = new Date();
    const birth = new Date(birthDate);
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    return age + " Tahun";
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Siswa", href: "/admin/student" },
];
</script>

<template>
    <Head title="Siswa" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Data Siswa"
                    description="Lihat dan kelola data siswa yang tersedia"
                />
                <Link
                    v-if="can('admin.student.create')"
                    :href="route('admin.student.create')"
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
                            <TableHead>Nama</TableHead>
                            <TableHead>Jenis Kelamin</TableHead>
                            <TableHead>Usia</TableHead>
                            <TableHead>Status Registrasi</TableHead>
                            <TableHead class="w-[10px]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="students.data.length > 0">
                            <TableRow
                                v-for="(item, index) in students.data"
                                :key="item.id"
                            >
                                <TableCell class="font-medium">
                                    {{ students.from + index }}
                                </TableCell>
                                <TableCell>
                                    {{ item.name }}
                                </TableCell>
                                <TableCell>
                                    {{ getGenderLabel(item?.gender) }}
                                </TableCell>
                                <TableCell>
                                    {{ getAge(item?.date_of_birth) }}
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            getStatusVariant(
                                                item.program_period_active
                                                    ?.status
                                            )
                                        "
                                    >
                                        {{
                                            getStatusLabel(
                                                item.program_period_active
                                                    ?.status
                                            )
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-center">
                                    <DropdownMenu
                                        v-if="
                                            canAny(
                                                'admin.student.edit',
                                                'admin.student.delete',
                                                'admin.student.show'
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
                                                v-if="can('admin.student.show')"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.student.show',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Detail
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                asChild
                                                v-if="can('admin.student.edit')"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.student.edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Ubah
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                v-if="
                                                    can('admin.student.delete')
                                                "
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
                                    <strong>Tidak ada data</strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
            <PaginationLinks :paginator="students" />
        </MainContent>
    </AppLayout>
    <AlertDialog :open="!!studentToDelete">
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
                <AlertDialogCancel @click="studentToDelete = null">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="destroy">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
