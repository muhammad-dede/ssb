<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import MainContent from "@/components/MainContent.vue";
import { Card, CardContent, CardFooter } from "@/components/ui/card/index";
import { Label } from "@/components/ui/label/index";
import { Button, buttonVariants } from "@/components/ui/button/index";
import InputError from "@/components/InputError.vue";
import { LoaderCircle } from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import HeadingSmall from "@/components/HeadingSmall.vue";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import InfoItem from "@/components/InfoItem.vue";
import { Switch } from "@/components/ui/switch";
import { Input } from "@/components/ui/input";
import { CircleDollarSign, Calendar } from "lucide-vue-next";

const props = defineProps({
    period_active: Object,
    program: Object,
    banks: Object,
    bank_accounts: Object,
});

const form = useForm({
    payment: false,
    accept: false,
    receiver_id: "",
    sender_bank_code: "",
    sender_account_number: "",
    sender_account_holder_name: "",
    proof_file: null,
    reference_number: "",
});

const getDefaultDueDate = () => {
    const date = new Date();
    date.setDate(date.getDate() + 7);
    const options = { day: "numeric", month: "long", year: "numeric" };
    return date.toLocaleDateString("id-ID", options);
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

const handleFileChange = (event) => {
    form.proof_file = event.target.files[0];
};

const submit = () => {
    form.post(route("student.student-program.store"), {
        preserveScroll: true,
    });
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Registrasi", href: "/student/student-program" },
    { title: "Registrasi Baru", href: "/student/student-program/create" },
];
</script>

<template>
    <Head title="Registrasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Registrasi"
                    description="Formulir untuk melakukan registrasi"
                />
            </HeadingGroup>
            <form @submit.prevent="submit">
                <div class="grid lg:grid-cols-3 gap-4">
                    <Card class="h-fit">
                        <CardContent>
                            <HeadingSmall title="Program Siswa" />
                            <div class="grid divide-y divide-gray-100">
                                <InfoItem
                                    label="Periode"
                                    :value="`${period_active.name}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                                <InfoItem
                                    label="Kode Program"
                                    :value="`${program.code}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                                <InfoItem
                                    label="Nama Program"
                                    :value="`${program.name}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                                <InfoItem
                                    label="Biaya Registrasi"
                                    :value="`${currency(
                                        program.registration_fee
                                    )}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                                <InfoItem
                                    label="Deskripsi"
                                    :value="`${program.description}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                            </div>
                        </CardContent>
                    </Card>
                    <Card class="h-fit lg:col-span-2">
                        <CardContent>
                            <HeadingSmall title="Tagihan Registrasi" />
                            <div class="grid divide-y divide-gray-100">
                                <InfoItem
                                    label="Biaya Registrasi"
                                    :value="`${currency(
                                        program.registration_fee
                                    )}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                                <InfoItem
                                    label="Jatuh Tempo"
                                    :value="getDefaultDueDate()"
                                    :icon="Calendar"
                                    background
                                />
                                <div class="py-4 space-y-4">
                                    <div
                                        class="flex flex-row items-center justify-between rounded-lg border p-4"
                                    >
                                        <div class="space-y-0.5">
                                            <Label
                                                for="payment"
                                                class="text-base"
                                            >
                                                Pembayaran
                                            </Label>
                                            <div
                                                class="text-sm text-muted-foreground"
                                            >
                                                Aktifkan jika Anda ingin
                                                melakukan pembayaran sekarang
                                            </div>
                                        </div>
                                        <Switch
                                            id="payment"
                                            v-model="form.payment"
                                        />
                                    </div>
                                    <div
                                        v-if="form.payment"
                                        class="grid rounded-lg border p-4"
                                    >
                                        <div class="flex flex-col gap-2 py-4">
                                            <Label for="receiver_id"
                                                >Bank Tujuan</Label
                                            >
                                            <Select
                                                v-model="form.receiver_id"
                                                name="receiver_id"
                                            >
                                                <SelectTrigger
                                                    id="receiver_id"
                                                    class="w-full"
                                                >
                                                    <SelectValue
                                                        placeholder="Pilih Bank"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectGroup>
                                                        <SelectItem
                                                            v-for="(
                                                                item, index
                                                            ) in bank_accounts"
                                                            :key="index"
                                                            :value="item.id"
                                                        >
                                                            {{
                                                                item.bank?.name
                                                            }}
                                                            -
                                                            {{
                                                                item.account_number
                                                            }}
                                                            a.n
                                                            {{
                                                                item.account_holder_name
                                                            }}
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                            <InputError
                                                :message="
                                                    form.errors.receiver_id
                                                "
                                            />
                                        </div>
                                        <div class="flex flex-col gap-2 py-4">
                                            <Label for="sender_bank_code"
                                                >Bank Pengirim</Label
                                            >
                                            <Select
                                                v-model="form.sender_bank_code"
                                                name="sender_bank_code"
                                            >
                                                <SelectTrigger
                                                    id="sender_bank_code"
                                                    class="w-full"
                                                >
                                                    <SelectValue
                                                        placeholder="Pilih Bank"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectGroup>
                                                        <SelectItem
                                                            v-for="(
                                                                item, index
                                                            ) in banks"
                                                            :key="index"
                                                            :value="item.code"
                                                        >
                                                            {{ item.name }}
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                            <InputError
                                                :message="
                                                    form.errors.sender_bank_code
                                                "
                                            />
                                        </div>
                                        <div class="flex flex-col gap-2 py-4">
                                            <Label for="sender_account_number"
                                                >No Rekening Pengirim</Label
                                            >
                                            <Input
                                                id="sender_account_number"
                                                type="text"
                                                name="sender_account_number"
                                                placeholder="Input No Rekening Pengirim"
                                                autocomplete="off"
                                                v-model="
                                                    form.sender_account_number
                                                "
                                            />
                                            <InputError
                                                :message="
                                                    form.errors
                                                        .sender_account_number
                                                "
                                            />
                                        </div>
                                        <div class="flex flex-col gap-2 py-4">
                                            <Label
                                                for="sender_account_holder_name"
                                                >Atas Nama Pengirim</Label
                                            >
                                            <Input
                                                id="sender_account_holder_name"
                                                type="text"
                                                name="sender_account_holder_name"
                                                placeholder="Input Atas Nama Pengirim"
                                                autocomplete="off"
                                                v-model="
                                                    form.sender_account_holder_name
                                                "
                                            />
                                            <InputError
                                                :message="
                                                    form.errors
                                                        .sender_account_holder_name
                                                "
                                            />
                                        </div>
                                        <div class="flex flex-col gap-2 py-4">
                                            <Label for="proof_file"
                                                >Bukti Transfer</Label
                                            >
                                            <Input
                                                id="proof_file"
                                                type="file"
                                                name="proof_file"
                                                accept="image/*"
                                                @change="handleFileChange"
                                            />
                                            <InputError
                                                :message="
                                                    form.errors.proof_file
                                                "
                                            />
                                        </div>
                                        <div class="flex flex-col gap-2 py-4">
                                            <Label for="reference_number"
                                                >No Referensi</Label
                                            >
                                            <Input
                                                id="reference_number"
                                                type="text"
                                                name="reference_number"
                                                placeholder="Input No Referensi"
                                                autocomplete="off"
                                                v-model="form.reference_number"
                                            />
                                            <InputError
                                                :message="
                                                    form.errors.reference_number
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-row items-center justify-between rounded-lg border p-4"
                                    >
                                        <div class="space-y-0.5">
                                            <Label
                                                for="accept"
                                                class="text-base"
                                            >
                                                Persetujuan Registrasi
                                            </Label>
                                            <div
                                                class="text-sm text-muted-foreground"
                                            >
                                                Saya bersedia mengikuti seluruh
                                                kegiatan dan jadwal yang telah
                                                ditentukan
                                            </div>
                                        </div>
                                        <Switch
                                            id="accept"
                                            v-model="form.accept"
                                        />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <div class="space-x-2">
                                <Button
                                    type="submit"
                                    :disabled="
                                        form.processing || form.accept === false
                                    "
                                >
                                    <LoaderCircle
                                        v-if="form.processing"
                                        class="h-4 w-4 animate-spin"
                                    />
                                    Registrasi
                                </Button>
                                <Link
                                    :href="
                                        route('student.student-program.index')
                                    "
                                    :class="
                                        buttonVariants({ variant: 'outline' })
                                    "
                                    >Kembali</Link
                                >
                            </div>
                        </CardFooter>
                    </Card>
                </div>
            </form>
        </MainContent>
    </AppLayout>
</template>
