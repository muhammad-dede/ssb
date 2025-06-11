<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Card, CardContent, CardFooter } from "@/components/ui/card/index";
import { Label } from "@/components/ui/label/index";
import { Input } from "@/components/ui/input/index";
import { Button, buttonVariants } from "@/components/ui/button/index";
import InputError from "@/components/InputError.vue";
import { LoaderCircle } from "lucide-vue-next";
import { Textarea } from "@/components/ui/textarea";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import LabelSpan from "@/components/LabelSpan.vue";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import usePermissions from "@/composables/usePermissions";

const { can } = usePermissions();

const props = defineProps({
    status_programs: Object,
    program: Object,
});

const form = useForm({
    code: props.program?.code ?? "",
    name: props.program?.name ?? "",
    age_min: props.program?.age_min ?? "",
    age_max: props.program?.age_max ?? "",
    description: props.program?.description ?? "",
    registration_fee: props.program?.registration_fee ?? "",
    status: props.program?.status ?? "",
});

const submit = () => {
    form.patch(route("program.update", props.program?.id));
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Program", href: "/program" },
    { title: "Ubah", href: "/program/edit" },
];
</script>

<template>
    <Head title="Ubah Program" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Ubah Program"
                    description="Formulir untuk mengubah data program yang telah terdaftar"
                />
            </HeadingGroup>
            <form @submit.prevent="submit">
                <Card>
                    <CardContent>
                        <Heading title="Informasi Program" />
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-x-2 gap-y-6 mb-4"
                        >
                            <div class="w-full flex flex-col gap-2">
                                <Label for="code">Kode</Label>
                                <Input
                                    id="code"
                                    type="text"
                                    name="code"
                                    placeholder="Input Kode"
                                    autocomplete="off"
                                    v-model="form.code"
                                />
                                <InputError :message="form.errors.code" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="name">Nama Program</Label>
                                <Input
                                    id="name"
                                    type="text"
                                    name="name"
                                    placeholder="Input Nama Program"
                                    autocomplete="off"
                                    v-model="form.name"
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="age_min">Minimal Usia</Label>
                                <Input
                                    id="age_min"
                                    type="number"
                                    name="age_min"
                                    placeholder="Input Minimal Usia"
                                    v-model="form.age_min"
                                />
                                <InputError :message="form.errors.age_min" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="age_max">Maksimal Usia</Label>
                                <Input
                                    id="age_max"
                                    type="number"
                                    name="age_max"
                                    placeholder="Input Maksimal Usia"
                                    v-model="form.age_max"
                                />
                                <InputError :message="form.errors.age_max" />
                            </div>
                            <div
                                class="w-full flex flex-col gap-2 md:col-span-2"
                            >
                                <Label for="description">Deskripsi</Label>
                                <Textarea
                                    id="description"
                                    name="description"
                                    placeholder="Input Deskripsi"
                                    autocomplete="off"
                                    v-model="form.description"
                                />
                                <InputError
                                    :message="form.errors.description"
                                />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="registration_fee"
                                    >Biaya Registrasi</Label
                                >
                                <Input
                                    id="registration_fee"
                                    type="text"
                                    name="registration_fee"
                                    placeholder="Input Biaya Registrasi"
                                    v-model="form.registration_fee"
                                />
                                <InputError
                                    :message="form.errors.registration_fee"
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
                                        v-for="(item, index) in status_programs"
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
                                v-if="can('program.index')"
                                :href="route('program.index')"
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
