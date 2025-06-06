<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Card, CardContent, CardFooter } from "@/components/ui/card/index";
import { Label } from "@/components/ui/label/index";
import { Input } from "@/components/ui/input/index";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Button, buttonVariants } from "@/components/ui/button/index";
import InputError from "@/components/InputError.vue";
import { LoaderCircle } from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import usePermissions from "@/composables/usePermissions";

const { can } = usePermissions();

defineProps({
    banks: Object,
});

const form = useForm({
    bank_code: "",
    account_number: "",
    account_holder_name: "",
});

const submit = () => {
    form.post(route("bank-account.store"));
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Akun Bank", href: "/bank-account" },
    { title: "Tambah", href: "/bank-account/create" },
];
</script>

<template>
    <Head title="Tambah Akun Bank" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Tambah Akun Bank"
                    description="Formulir untuk menambahkan data akun bank baru"
                />
            </HeadingGroup>
            <form @submit.prevent="submit">
                <Card>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-x-2 gap-y-6 mb-4"
                        >
                            <div class="w-full flex flex-col gap-2">
                                <Label for="bank_code">Bank</Label>
                                <Select
                                    v-model="form.bank_code"
                                    name="bank_code"
                                >
                                    <SelectTrigger
                                        id="bank_code"
                                        class="w-full"
                                    >
                                        <SelectValue placeholder="Pilih Bank" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="item in banks"
                                                :key="item.id"
                                                :value="item.code"
                                            >
                                                {{ item.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.bank_code" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="account_number"
                                    >Nomor Rekening</Label
                                >
                                <Input
                                    id="account_number"
                                    type="text"
                                    name="account_number"
                                    placeholder="Input Nomor Rekening"
                                    autocomplete="off"
                                    v-model="form.account_number"
                                />
                                <InputError
                                    :message="form.errors.account_number"
                                />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="account_holder_name"
                                    >Atas Nama Pemilik</Label
                                >
                                <Input
                                    id="account_holder_name"
                                    type="text"
                                    name="account_holder_name"
                                    placeholder="Input Atas Nama Pemilik"
                                    autocomplete="off"
                                    v-model="form.account_holder_name"
                                />
                                <InputError
                                    :message="form.errors.account_holder_name"
                                />
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <div class="space-x-2">
                            <Button type="submit" :disabled="form.processing">
                                <LoaderCircle
                                    v-if="form.processing"
                                    class="h-4 w-4 animate-spin"
                                />
                                Simpan
                            </Button>
                            <Link
                                v-if="can('bank-account.index')"
                                :href="route('bank-account.index')"
                                :class="buttonVariants({ variant: 'outline' })"
                                >Kembali</Link
                            >
                        </div>
                    </CardFooter>
                </Card>
            </form>
        </MainContent>
    </AppLayout>
</template>
