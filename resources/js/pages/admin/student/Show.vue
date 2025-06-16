<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Card, CardContent } from "@/components/ui/card/index";
import { Button } from "@/components/ui/button/index";
import {
    Phone,
    Calendar,
    Mail,
    Mars,
    MapPinCheck,
    IdCard,
    MoreHorizontal,
    Trash2,
    Pencil,
    Undo2,
    Footprints,
    Ruler,
    Weight,
} from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import InfoItem from "@/components/InfoItem.vue";
import { ref } from "vue";
import usePermissions from "@/composables/usePermissions";
import Lightbox from "@/components/Lightbox.vue";
import { Badge } from "@/components/ui/badge/index";
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

const { can } = usePermissions();

const props = defineProps({
    dominant_foots: Object,
    student: Object,
});

const showPhoto = ref(false);
const togglePhoto = () => {
    showPhoto.value = !showPhoto.value;
};

const showConfirmDelete = ref(false);
const destroy = () => {
    showConfirmDelete.value = false;
    router.delete(route("admin.student.destroy", props.student.id), {
        preserveScroll: true,
    });
};

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Siswa", href: "/admin/student" },
    { title: "Detail", href: "/admin/student/show" },
];
</script>

<template>
    <Head title="Detail Siswa" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Detail Siswa"
                    description="Informasi lengkap mengenai siswa yang terdaftar"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="w-8 h-8 p-0">
                            <MoreHorizontal class="w-4 h-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem
                            v-if="can('admin.student.index')"
                            asChild
                        >
                            <Link :href="route('admin.student.index')">
                                <Undo2 class="text-yellow-500" />
                                Kembali
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="can('admin.student.edit')"
                            asChild
                        >
                            <Link
                                :href="route('admin.student.edit', student.id)"
                            >
                                <Pencil class="text-green-500" />
                                Ubah Data
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="can('admin.student.delete')"
                            @select="showConfirmDelete = true"
                        >
                            <Trash2 class="text-red-500" />
                            Hapus Data
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </HeadingGroup>
            <div class="flex flex-col lg:flex-row gap-4">
                <Card class="h-fit w-full lg:w-[50%] xl:w-[60%] py-3">
                    <CardContent>
                        <h5 class="text-sm font-bold text-gray-500 mb-4">
                            Informasi Biodata
                        </h5>
                        <div class="flex justify-between">
                            <template v-if="student.photo_url">
                                <img
                                    :src="student.photo_url"
                                    alt="Preview"
                                    class="h-24 w-24 object-cover rounded-lg border cursor-pointer"
                                    @click="togglePhoto"
                                />
                            </template>
                            <template v-else>
                                <div
                                    class="h-24 w-24 flex items-center justify-center border rounded-lg text-gray-500 text-xs"
                                >
                                    Belum ada gambar
                                </div>
                            </template>
                            <Badge
                                :variant="
                                    student.program_period_active
                                        ?.status_variant ?? 'destructive'
                                "
                                class="py-2 px-3 rounded-full h-fit"
                            >
                                {{
                                    student.program_period_active
                                        ?.status_label ?? "BELUM TERDAFTAR"
                                }}
                            </Badge>
                        </div>
                        <div class="grid divide-y divide-gray-100">
                            <InfoItem
                                :label="student.national_id_number"
                                :value="student.name"
                                :icon="IdCard"
                            />
                            <InfoItem
                                label="Tempat Lahir"
                                :value="student.place_of_birth"
                                :icon="MapPinCheck"
                                background
                            />
                            <InfoItem
                                label="Tanggal Lahir"
                                :value="dateFormat(student.date_of_birth)"
                                :icon="Calendar"
                                background
                            />
                            <InfoItem
                                label="Jenis Kelamin"
                                :value="student?.gender_label"
                                :icon="Mars"
                                background
                            />
                            <InfoItem
                                label="Alamat"
                                :value="student.address"
                                :icon="MapPinCheck"
                                background
                            />
                            <InfoItem
                                label="Telepon"
                                :value="student.phone"
                                :icon="Phone"
                                background
                            />
                            <InfoItem
                                label="Email"
                                :value="student.user?.email"
                                :icon="Mail"
                                background
                            />
                        </div>
                    </CardContent>
                </Card>
                <Card class="h-fit w-full lg:w-[50%] xl:w-[40%] py-3">
                    <CardContent>
                        <h5 class="text-sm font-bold text-gray-500 mb-4">
                            Informasi Fisik
                        </h5>
                        <div class="grid divide-y divide-gray-100">
                            <InfoItem
                                label="Kaki Dominan"
                                :value="student.dominant_foot_label"
                                :icon="Footprints"
                                background
                            />
                            <InfoItem
                                label="Tinggi Badan"
                                :value="`${
                                    student.height_cm ?? '-'
                                } Centimeter`"
                                :icon="Ruler"
                                background
                            />
                            <InfoItem
                                label="Berat Badan"
                                :value="`${student.weight_kg ?? '-'} Kg`"
                                :icon="Weight"
                                background
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>
        </MainContent>
    </AppLayout>

    <Lightbox
        :show="showPhoto"
        :image-url="student.photo_url"
        @close="togglePhoto"
    />

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
</template>
