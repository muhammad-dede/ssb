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
    CreditCard,
    FileDigit,
    Landmark,
    MoreHorizontal,
    Trash2,
    Pencil,
    CirclePower,
    Undo2,
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
    genders: Object,
    status_coaches: Object,
    coach: Object,
});

const showPhoto = ref(false);
const togglePhoto = () => {
    showPhoto.value = !showPhoto.value;
};

const showConfirmStatus = ref(false);
const changeStatus = () => {
    showConfirmStatus.value = false;
    router.post(route("coach.status", props.coach.id), {
        preserveScroll: true,
    });
};

const showConfirmDelete = ref(false);
const destroy = () => {
    showConfirmDelete.value = false;
    router.delete(route("coach.destroy", props.coach.id), {
        preserveScroll: true,
    });
};

const getGenderLabel = (gender) => {
    if (!gender) return "-";
    const found = props.genders?.find((item) => item.value === gender);
    return found?.label ?? "-";
};

const getStatusLabel = (status) => {
    if (!status) return "-";
    const found = props.status_coaches?.find((item) => item.value === status);
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
const getStatusChangeLabel = (status) => {
    if (!status) return "Aktifkan";
    switch (status) {
        case "ACTIVE":
            return "Nonaktifkan";
        case "INACTIVE":
            return "Aktifkan";
        default:
            return "Aktifkan";
    }
};

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Pelatih", href: "/coach" },
    { title: "Detail", href: "/coach/show" },
];
</script>

<template>
    <Head title="Detail Pelatih" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Detail Pelatih"
                    description="Informasi lengkap mengenai pelatih yang terdaftar"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="w-8 h-8 p-0">
                            <MoreHorizontal class="w-4 h-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem v-if="can('coach.index')" asChild>
                            <Link :href="route('coach.index')">
                                <Undo2 class="text-yellow-500" />
                                Kembali
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="can('coach.edit')"
                            @select="showConfirmStatus = true"
                        >
                            <CirclePower class="text-blue-500" />
                            {{ getStatusChangeLabel(coach?.status) }}
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="can('coach.edit')" asChild>
                            <Link :href="route('coach.edit', coach.id)">
                                <Pencil class="text-green-500" />
                                Ubah Data
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="can('coach.delete')"
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
                            <template v-if="coach.photo_url">
                                <img
                                    :src="coach.photo_url"
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
                                :variant="getStatusVariant(coach?.status)"
                                class="py-2 px-3 rounded-full h-fit"
                            >
                                {{ getStatusLabel(coach?.status) }}
                            </Badge>
                        </div>
                        <div class="grid divide-y divide-gray-100">
                            <InfoItem
                                :label="coach.national_id_number"
                                :value="coach.name"
                                :icon="IdCard"
                            />
                            <InfoItem
                                label="Tempat Lahir"
                                :value="coach.place_of_birth"
                                :icon="MapPinCheck"
                                background
                            />
                            <InfoItem
                                label="Tanggal Lahir"
                                :value="dateFormat(coach.date_of_birth)"
                                :icon="Calendar"
                                background
                            />
                            <InfoItem
                                label="Jenis Kelamin"
                                :value="getGenderLabel(coach?.gender)"
                                :icon="Mars"
                                background
                            />
                            <InfoItem
                                label="Alamat"
                                :value="coach.address"
                                :icon="MapPinCheck"
                                background
                            />
                            <InfoItem
                                label="Telepon"
                                :value="coach.phone"
                                :icon="Phone"
                                background
                            />
                            <InfoItem
                                label="Email"
                                :value="coach.user?.email"
                                :icon="Mail"
                                background
                            />
                        </div>
                    </CardContent>
                </Card>
                <Card class="h-fit w-full lg:w-[50%] xl:w-[40%] py-3">
                    <CardContent>
                        <h5 class="text-sm font-bold text-gray-500 mb-4">
                            Informasi Kepelatihan
                        </h5>
                        <div class="grid divide-y divide-gray-100">
                            <InfoItem
                                label="Lisensi Kepelatihan"
                                :value="coach.coaching_license ?? '-'"
                                :icon="CreditCard"
                                background
                            />
                            <InfoItem
                                label="Nomor Lisensi"
                                :value="coach.license_number ?? '-'"
                                :icon="FileDigit"
                                background
                            />
                            <InfoItem
                                label="Tanggal Terbit"
                                :value="
                                    dateFormat(coach.license_issued_at) ?? '-'
                                "
                                :icon="Calendar"
                                background
                            />
                            <InfoItem
                                label="Tanggal Berakhir"
                                :value="
                                    dateFormat(coach.license_expired_at) ?? '-'
                                "
                                :icon="Calendar"
                                background
                            />
                            <InfoItem
                                label="Lembaga Kepelatihan"
                                :value="coach.license_issuer ?? '-'"
                                :icon="Landmark"
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
        :image-url="coach.photo_url"
        @close="togglePhoto"
    />

    <AlertDialog :open="!!showConfirmStatus">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>
                    Apakah Anda benar-benar yakin?
                </AlertDialogTitle>
                <AlertDialogDescription>
                    Status Pelatih akan di-{{
                        getStatusChangeLabel(coach?.status ?? null)
                    }}.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="showConfirmStatus = false">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="changeStatus">{{
                    getStatusChangeLabel(coach?.status ?? null)
                }}</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>

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
