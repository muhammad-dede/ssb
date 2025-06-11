<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Card, CardContent, CardFooter } from "@/components/ui/card/index";
import { Label } from "@/components/ui/label/index";
import { Button, buttonVariants } from "@/components/ui/button/index";
import InputError from "@/components/InputError.vue";
import { LoaderCircle } from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import usePermissions from "@/composables/usePermissions";
import InfoItem from "@/components/InfoItem.vue";
import { CircleDollarSign, Calendar } from "lucide-vue-next";
import { watch } from "vue";

const { can } = usePermissions();

const props = defineProps({
    students: Object,
    programs: Object,
    periods: Object,
    student_program: Object,
});

const getDefaultDueDate = () => {
    const date = new Date();
    date.setDate(date.getDate() + 7);
    return date.toISOString().split("T")[0];
};

const form = useForm({
    student_id: "",
    program_code: "",
    period_id: "",
    amount: 0,
    due_date: getDefaultDueDate(),
});

watch(
    () => form.program_code,
    (newCode) => {
        const program = props.programs.find(
            (program) => program.code === newCode
        );
        form.amount = program?.registration_fee ?? 0;
    }
);

const currency = (number) => {
    if (isNaN(number)) return "Rp0";
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(number));
};

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const submit = () => {
    form.post(route("student-program.store"), {
        preserveScroll: true,
    });
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Registrasi", href: "/student-program" },
    { title: "Tambah", href: "/student-program/create" },
];
</script>

<template>
    <Head title="Tambah Registrasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Tambah Registrasi"
                    description="Formulir untuk menambahkan data registrasi siswa baru"
                />
            </HeadingGroup>
            <form @submit.prevent="submit">
                <div class="grid lg:grid-cols-3 gap-4">
                    <Card class="h-fit lg:col-span-2">
                        <CardContent>
                            <Heading title="Informasi Registrasi" />
                            <div class="grid gap-x-2 gap-y-6 my-4">
                                <div class="w-full flex flex-col gap-2">
                                    <Label for="period_id"
                                        >Periode Yang Diikuti</Label
                                    >
                                    <Select
                                        v-model="form.period_id"
                                        name="period_id"
                                    >
                                        <SelectTrigger
                                            id="period_id"
                                            class="w-full"
                                        >
                                            <SelectValue
                                                placeholder="Pilih Periode"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem
                                                    v-for="(
                                                        item, index
                                                    ) in periods"
                                                    :key="index"
                                                    :value="item.id"
                                                >
                                                    {{ item.name }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <InputError
                                        :message="form.errors.period_id"
                                    />
                                </div>
                                <div class="w-full flex flex-col gap-2">
                                    <Label for="student_id">Siswa</Label>
                                    <Select
                                        v-model="form.student_id"
                                        name="student_id"
                                    >
                                        <SelectTrigger
                                            id="student_id"
                                            class="w-full"
                                        >
                                            <SelectValue
                                                placeholder="Pilih Siswa"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem
                                                    v-for="(
                                                        item, index
                                                    ) in students"
                                                    :key="index"
                                                    :value="item.id"
                                                >
                                                    {{ item.name }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <InputError
                                        :message="form.errors.student_id"
                                    />
                                </div>
                                <div class="w-full flex flex-col gap-2">
                                    <Label for="program_code"
                                        >Program Yang Diikuti</Label
                                    >
                                    <Select
                                        v-model="form.program_code"
                                        name="program_code"
                                    >
                                        <SelectTrigger
                                            id="program_code"
                                            class="w-full"
                                        >
                                            <SelectValue
                                                placeholder="Pilih Program"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem
                                                    v-for="(
                                                        item, index
                                                    ) in programs"
                                                    :key="index"
                                                    :value="item.code"
                                                >
                                                    {{ item.name }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <InputError
                                        :message="form.errors.program_code"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <Card class="h-fit">
                        <CardContent>
                            <Heading title="Informasi Tagihan" />
                            <div class="grid divide-y divide-gray-100">
                                <InfoItem
                                    label="Biaya Registrasi"
                                    :value="`${currency(form.amount)}`"
                                    :icon="CircleDollarSign"
                                    background
                                />
                                <InfoItem
                                    label="Jatuh Tempo"
                                    :value="`${dateFormat(form.due_date)}`"
                                    :icon="Calendar"
                                    background
                                />
                            </div>
                        </CardContent>
                        <CardFooter>
                            <div class="space-x-2">
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    <LoaderCircle
                                        v-if="form.processing"
                                        class="h-4 w-4 animate-spin"
                                    />
                                    Simpan
                                </Button>
                                <Link
                                    v-if="can('student-program.index')"
                                    :href="route('student-program.index')"
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
