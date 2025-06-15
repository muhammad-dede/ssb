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
    Undo2,
    Bookmark,
    BookOpenCheck,
    CircleDollarSign,
    CreditCard,
    FileQuestion,
    BanknoteArrowDown,
    BanknoteArrowUp,
    TicketSlash,
    NotebookPen,
    CloudUpload,
} from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import InfoItem from "@/components/InfoItem.vue";
import { ref } from "vue";
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
import Payment from "./Payment.vue";

const props = defineProps({
    payment_methods: Object,
    bank_accounts: Object,
    banks: Object,
    student_program: Object,
});

const showConfirmDelete = ref(false);

const destroy = () => {
    showConfirmDelete.value = false;
    router.delete(
        route("student.student-program.destroy", props.student_program?.id),
        {
            preserveScroll: true,
        }
    );
};

const getPaymentMethodLabel = (method) => {
    if (!method) return "-";
    const found = props.payment_methods?.find((item) => item.value === method);
    return found?.label?.toUpperCase() ?? "-";
};

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const currency = (number) => {
    if (isNaN(number)) return "Rp0";
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(number));
};

const showPhoto = ref(false);
const togglePhoto = () => {
    showPhoto.value = !showPhoto.value;
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Registrasi", href: "/student/student-program" },
    { title: "Detail", href: "/student/student-program/show" },
];
</script>

<template>
    <Head title="Detail Registrasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Detail Registrasi"
                    description="Informasi lengkap mengenai registrasi siswa yang terdaftar"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="w-8 h-8 p-0">
                            <MoreHorizontal class="w-4 h-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem asChild>
                            <Link
                                :href="route('student.student-program.index')"
                            >
                                <Undo2 class="text-yellow-500" />
                                Kembali
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="student_program.can_delete"
                            @select="showConfirmDelete = true"
                        >
                            <Trash2 class="text-red-500" />
                            Hapus Data
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </HeadingGroup>
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="h-fit w-full lg:w-[50%] xl:w-[60%]">
                    <Card>
                        <CardContent>
                            <h5 class="text-sm font-bold text-gray-500 mb-4">
                                Informasi Program Yang Diikuti
                            </h5>
                            <div class="grid divide-y divide-gray-100">
                                <div class="flex justify-between items-center">
                                    <InfoItem
                                        label="Program Yang Diikuti"
                                        :value="student_program.program?.name"
                                        :icon="BookOpenCheck"
                                        background
                                    />
                                    <Badge
                                        :variant="
                                            student_program?.status_variant
                                        "
                                        class="py-2 px-3 rounded-full h-fit"
                                    >
                                        {{ student_program?.status_label }}
                                    </Badge>
                                </div>
                                <InfoItem
                                    label="Periode"
                                    :value="student_program.period?.name"
                                    :icon="Bookmark"
                                    background
                                />
                                <InfoItem
                                    label="Invoice"
                                    :value="`${student_program?.billing?.invoice}`"
                                    :icon="CreditCard"
                                    background
                                />
                                <InfoItem
                                    label="Biaya Pendaftaran"
                                    :value="`${currency(
                                        student_program?.billing?.amount
                                    )}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                                <InfoItem
                                    label="Jatuh Tempo"
                                    :value="`${dateFormat(
                                        student_program?.billing?.due_date
                                    )}`"
                                    :icon="Calendar"
                                    background
                                />
                            </div>
                        </CardContent>
                    </Card>
                </div>
                <div class="h-fit w-full lg:w-[50%] xl:w-[40%] space-y-4">
                    <Card>
                        <CardContent>
                            <h5 class="text-sm font-bold text-gray-500 mb-4">
                                Informasi Pembayaran
                            </h5>
                            <div
                                v-if="student_program?.billing?.payment"
                                class="grid divide-y divide-gray-100"
                            >
                                <InfoItem
                                    label="Jumlah Yang Dibayarkan"
                                    :value="`${currency(
                                        student_program?.billing?.payment
                                            ?.amount
                                    )}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                                <InfoItem
                                    label="Tanggal Bayar"
                                    :value="`${dateFormat(
                                        student_program?.billing?.payment
                                            ?.payment_date
                                    )}`"
                                    :icon="Calendar"
                                    background
                                />
                                <InfoItem
                                    label="Metode Pembayaran"
                                    :value="
                                        getPaymentMethodLabel(
                                            student_program?.billing?.payment
                                                ?.method
                                        )
                                    "
                                    :icon="CreditCard"
                                    background
                                />
                                <template
                                    v-if="
                                        student_program?.billing?.payment
                                            ?.method === 'TRANSFER'
                                    "
                                >
                                    <InfoItem
                                        label="Penerima"
                                        :value="`${student_program?.billing?.payment?.receiver_bank?.name} - ${student_program?.billing?.payment?.receiver_account_number} a.n ${student_program?.billing?.payment?.receiver_account_holder_name}`"
                                        :icon="BanknoteArrowDown"
                                        background
                                    />
                                    <InfoItem
                                        label="Pengirim"
                                        :value="`${student_program?.billing?.payment?.sender_bank?.name} - ${student_program?.billing?.payment?.sender_account_number} a.n ${student_program?.billing?.payment?.sender_account_holder_name}`"
                                        :icon="BanknoteArrowUp"
                                        background
                                    />
                                    <div
                                        class="cursor-pointer"
                                        @click="togglePhoto"
                                    >
                                        <InfoItem
                                            label="Bukti Transfer"
                                            value="Lihat"
                                            :icon="CloudUpload"
                                            background
                                        />
                                    </div>
                                    <InfoItem
                                        label="No. Referensi"
                                        :value="
                                            student_program?.billing?.payment
                                                ?.reference_number
                                        "
                                        :icon="TicketSlash"
                                        background
                                    />
                                </template>
                                <InfoItem
                                    label="Catatan"
                                    :value="
                                        student_program?.billing?.payment
                                            ?.notes ?? '-'
                                    "
                                    :icon="NotebookPen"
                                    background
                                />
                                <div
                                    v-if="
                                        student_program?.billing?.payment
                                            ?.method === 'TRANSFER' &&
                                        student_program?.billing?.payment
                                            ?.can_edit
                                    "
                                    class="pt-4"
                                >
                                    <Payment
                                        label="Ubah Pembayaran"
                                        :student_program="student_program"
                                        :bank_accounts="bank_accounts"
                                        :banks="banks"
                                    />
                                </div>
                            </div>
                            <div
                                v-else
                                class="flex flex-col gap-6 justify-center items-center w-full"
                            >
                                <div
                                    class="flex flex-col gap-2 justify-center items-center text-gray-500"
                                >
                                    <FileQuestion class="size-20" />
                                    <p class="font-semibold text-sm">
                                        Belum ada pembayaran
                                    </p>
                                </div>
                                <Payment
                                    label="Bayar"
                                    :student_program="student_program"
                                    :bank_accounts="bank_accounts"
                                    :banks="banks"
                                />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </MainContent>
    </AppLayout>

    <Lightbox
        :show="showPhoto"
        :image-url="student_program?.billing?.payment?.proof_file_url"
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
