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
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import InputError from "@/components/InputError.vue";
import { LoaderCircle } from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import LabelSpan from "@/components/LabelSpan.vue";
import usePermissions from "@/composables/usePermissions";

const { can } = usePermissions();

defineProps({
    roles: Object,
    status_users: Object,
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "",
    status: "",
});

const submit = () => {
    form.post(route("user.store"));
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Pengguna", href: "/user" },
    { title: "Tambah", href: "/user/create" },
];
</script>

<template>
    <Head title="Tambah Pengguna" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Tambah Pengguna"
                    description="Formulir untuk menambahkan data pengguna baru"
                />
            </HeadingGroup>
            <form @submit.prevent="submit">
                <Card>
                    <CardContent>
                        <Heading title="Informasi Pengguna" />
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-x-2 gap-y-6 mb-4"
                        >
                            <div class="w-full flex flex-col gap-2">
                                <Label for="name">Nama</Label>
                                <Input
                                    id="name"
                                    type="text"
                                    name="name"
                                    placeholder="Input Nama"
                                    autocomplete="off"
                                    v-model="form.name"
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder="Input Email"
                                    autocomplete="off"
                                    v-model="form.email"
                                />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="role">Role</Label>
                                <Select v-model="form.role" name="role">
                                    <SelectTrigger id="role" class="w-full">
                                        <SelectValue placeholder="Pilih Role" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="role in roles"
                                                :key="role.id"
                                                :value="role.name"
                                            >
                                                {{ role.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.role" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="password">Password</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Input Password"
                                    autocomplete="off"
                                    v-model="form.password"
                                />
                                <InputError :message="form.errors.password" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="password_confirmation"
                                    >Konfirmasi Password</Label
                                >
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Input Konfirmasi Password"
                                    autocomplete="off"
                                    v-model="form.password_confirmation"
                                />
                            </div>
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <LabelSpan label="Pilih Status" />
                                <RadioGroup
                                    :orientation="'vertical'"
                                    v-model="form.status"
                                >
                                    <div
                                        class="flex items-center space-x-2"
                                        v-for="(item, index) in status_users"
                                        :key="index"
                                    >
                                        <RadioGroupItem
                                            :id="`status-${index}`"
                                            :value="item.value"
                                        />
                                        <Label :for="`status-${index}`">
                                            {{ item.label }}
                                        </Label>
                                    </div>
                                </RadioGroup>
                                <InputError :message="form.errors.status" />
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
                                v-if="can('user.index')"
                                :href="route('user.index')"
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
