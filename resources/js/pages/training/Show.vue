<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Card, CardContent } from "@/components/ui/card/index";
import { Button } from "@/components/ui/button/index";
import {
    Calendar,
    MoreHorizontal,
    Trash2,
    Pencil,
    Undo2,
    Group,
    CalendarDays,
    UserCog,
    Timer,
    MapPin,
    NotebookPen,
    CirclePower,
} from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import InfoItem from "@/components/InfoItem.vue";
import { ref } from "vue";
import usePermissions from "@/composables/usePermissions";
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
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Badge } from "@/components/ui/badge/index";

const { can } = usePermissions();

const props = defineProps({
    status_trainings: Object,
    variants: Object,
    training: Object,
});

const showConfirmStatus = ref(false);
const changeStatus = () => {
    showConfirmStatus.value = false;
    router.post(route("training.status", props.training.id), {
        preserveScroll: true,
    });
};

const showConfirmDelete = ref(false);
const destroy = () => {
    showConfirmDelete.value = false;
    router.delete(route("training.destroy", props.training?.id), {
        preserveScroll: true,
    });
};

const getStatusLabel = (status) => {
    if (!status) return "-";
    const found = props.status_trainings?.find((item) => item.value === status);
    return found?.label?.toUpperCase() ?? "-";
};
const getVariant = (status) => {
    if (!status) return "outline";
    const found = props.variants?.find((item) => item.value === status);
    return found?.label ?? "outline";
};

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Latihan", href: "/training" },
    { title: "Detail", href: "/training/show" },
];
</script>

<template>
    <Head title="Detail Latihan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Detail Latihan"
                    description="Informasi lengkap mengenai latihan yang terdaftar"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="w-8 h-8 p-0">
                            <MoreHorizontal class="w-4 h-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem v-if="can('training.index')" asChild>
                            <Link :href="route('training.index')">
                                <Undo2 class="text-yellow-500" />
                                Kembali
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="can('training.edit')"
                            @select="showConfirmStatus = true"
                        >
                            <CirclePower class="text-blue-500" />
                            Ubah Status
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="can('training.edit')" asChild>
                            <Link :href="route('training.edit', training.id)">
                                <Pencil class="text-green-500" />
                                Ubah Data
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="can('training.delete')"
                            @select="showConfirmDelete = true"
                        >
                            <Trash2 class="text-red-500" />
                            Hapus Data
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </HeadingGroup>
            <Card>
                <CardContent>
                    <h5 class="text-sm font-bold text-gray-500 mb-4">
                        Informasi Latihan
                    </h5>
                    <div class="grid divide-y divide-gray-100">
                        <div class="flex justify-between items-center">
                            <InfoItem
                                label="Periode"
                                :value="training.period?.name ?? '-'"
                                :icon="CalendarDays"
                                background
                            />
                            <Badge
                                :variant="getVariant(training?.status)"
                                class="py-2 px-3 rounded-full h-fit"
                            >
                                {{ getStatusLabel(training?.status) }}
                            </Badge>
                        </div>
                        <InfoItem
                            label="Program"
                            :value="training.program?.name ?? '-'"
                            :icon="Group"
                            background
                        />
                        <InfoItem
                            label="Pelatih"
                            :value="training.coach?.name ?? '-'"
                            :icon="UserCog"
                            background
                        />
                        <InfoItem
                            label="Tanggal Pelatihan"
                            :value="dateFormat(training.training_date) ?? '-'"
                            :icon="Calendar"
                            background
                        />
                        <InfoItem
                            label="Waktu Pelatihan"
                            :value="
                                `${training.start_time} - ${training.end_time}` ??
                                '-'
                            "
                            :icon="Timer"
                            background
                        />
                        <InfoItem
                            label="Lokasi"
                            :value="training.location ?? '-'"
                            :icon="MapPin"
                            background
                        />
                        <InfoItem
                            label="Deskripsi"
                            :value="training.description ?? '-'"
                            :icon="NotebookPen"
                            background
                        />
                    </div>
                </CardContent>
            </Card>
        </MainContent>
    </AppLayout>

    <AlertDialog :open="!!showConfirmDelete">
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
                <AlertDialogCancel @click="showConfirmDelete = false">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="destroy">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>

    <AlertDialog :open="!!showConfirmStatus">
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
                <AlertDialogCancel @click="showConfirmStatus = false">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="changeStatus"
                    >Ubah Status</AlertDialogAction
                >
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
