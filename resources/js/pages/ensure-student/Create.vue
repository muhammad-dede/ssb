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
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import { Separator } from "@/components/ui/separator";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import LabelSpan from "@/components/LabelSpan.vue";
import { Textarea } from "@/components/ui/textarea";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { onUnmounted, ref } from "vue";

const props = defineProps({
    genders: Object,
    dominant_foots: Object,
    user: Object,
});

const form = useForm({
    name: props.user?.name ?? "",
    place_of_birth: "",
    date_of_birth: "",
    gender: "",
    address: "",
    phone: "",
    national_id_number: "",
    photo: null,
    dominant_foot: "",
    height_cm: "",
    weight_kg: "",
});

const photoPreview = ref(null);
const handlePhoto = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.photo = file;
        if (photoPreview.value) {
            URL.revokeObjectURL(photoPreview.value);
        }
        photoPreview.value = URL.createObjectURL(file);
    }
};

onUnmounted(() => {
    if (photoPreview.value) {
        URL.revokeObjectURL(photoPreview.value);
    }
});

const submit = () => {
    form.post(route("ensure.student.store"), {
        forceFormData: true,
        preserveScroll: true,
    });
};

const breadcrumbs = [
    {
        title: "Lengkapi Data Diri",
        href: "/ensure/student",
    },
];
</script>

<template>
    <Head title="Lengkapi Data Diri" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Lengkapi Data Diri"
                    description="Lengkapi data diri Anda dibawah ini"
                />
            </HeadingGroup>
            <form @submit.prevent="submit">
                <Card>
                    <CardContent>
                        <Heading title="Informasi Data Diri" />
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-x-2 gap-y-6 my-4"
                        >
                            <div
                                class="w-full flex flex-col gap-2 md:col-span-2"
                            >
                                <Label for="photo">Foto</Label>
                                <label for="photo" class="cursor-pointer w-fit">
                                    <template v-if="photoPreview">
                                        <img
                                            :src="photoPreview"
                                            alt="Preview"
                                            class="h-32 w-32 object-cover rounded-lg border"
                                        />
                                    </template>
                                    <template v-else>
                                        <div
                                            class="h-32 w-32 flex items-center justify-center border rounded-lg text-gray-500 text-xs"
                                        >
                                            Pilih Gambar
                                        </div>
                                    </template>
                                </label>
                                <Input
                                    id="photo"
                                    type="file"
                                    name="photo"
                                    accept="image/*"
                                    class="hidden"
                                    @change="handlePhoto"
                                />
                                <InputError :message="form.errors.photo" />
                            </div>
                            <div
                                class="w-full flex flex-col gap-2 md:col-span-2"
                            >
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
                                <Label for="place_of_birth">Tempat Lahir</Label>
                                <Input
                                    id="place_of_birth"
                                    type="text"
                                    name="place_of_birth"
                                    placeholder="Input Tempat Lahir"
                                    autocomplete="off"
                                    v-model="form.place_of_birth"
                                />
                                <InputError
                                    :message="form.errors.place_of_birth"
                                />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="date_of_birth">Tanggal Lahir</Label>
                                <Input
                                    id="date_of_birth"
                                    type="date"
                                    name="date_of_birth"
                                    placeholder="Input Tanggal Lahir"
                                    autocomplete="off"
                                    v-model="form.date_of_birth"
                                />
                                <InputError
                                    :message="form.errors.date_of_birth"
                                />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <LabelSpan label="Jenis Kelamin" />
                                <RadioGroup
                                    :default-value="form.gender"
                                    :orientation="'vertical'"
                                    v-model="form.gender"
                                >
                                    <div
                                        class="flex items-center space-x-2"
                                        v-for="(gender, index) in genders"
                                        :key="index"
                                    >
                                        <RadioGroupItem
                                            :id="`gender-${index}`"
                                            :value="gender.value"
                                        />
                                        <Label :for="`gender-${index}`">
                                            {{ gender.label }}
                                        </Label>
                                    </div>
                                </RadioGroup>
                                <InputError :message="form.errors.gender" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="phone">Telepon</Label>
                                <Input
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    placeholder="Input Telepon"
                                    autocomplete="off"
                                    v-model="form.phone"
                                />
                                <InputError :message="form.errors.phone" />
                            </div>
                            <div
                                class="w-full flex flex-col gap-2 md:col-span-2"
                            >
                                <Label for="address">Alamat</Label>
                                <Textarea
                                    id="address"
                                    name="address"
                                    placeholder="Input Alamat"
                                    autocomplete="off"
                                    v-model="form.address"
                                />
                                <InputError :message="form.errors.address" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="national_id_number"
                                    >No. Identitas</Label
                                >
                                <Input
                                    id="national_id_number"
                                    type="text"
                                    name="national_id_number"
                                    placeholder="Input No. Identitas"
                                    autocomplete="off"
                                    v-model="form.national_id_number"
                                />
                                <InputError
                                    :message="form.errors.national_id_number"
                                />
                            </div>
                        </div>
                        <Separator class="my-4" />
                        <Heading title="Informasi Fisik" />
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-x-2 gap-y-6 my-4"
                        >
                            <div class="w-full flex flex-col gap-2">
                                <Label for="dominant_foot">Kaki Dominan</Label>
                                <Select
                                    v-model="form.dominant_foot"
                                    name="dominant_foot"
                                >
                                    <SelectTrigger
                                        id="dominant_foot"
                                        class="w-full"
                                    >
                                        <SelectValue
                                            placeholder="Pilih Kaki Dominan"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="(
                                                    item, index
                                                ) in dominant_foots"
                                                :key="index"
                                                :value="item.value"
                                            >
                                                {{ item.label }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError
                                    :message="form.errors.dominant_foot"
                                />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="height_cm"
                                    >Tinggi Badan (centimeter)</Label
                                >
                                <Input
                                    id="height_cm"
                                    type="number"
                                    name="height_cm"
                                    placeholder="Input Tinggi Badan"
                                    autocomplete="off"
                                    v-model="form.height_cm"
                                />
                                <InputError :message="form.errors.height_cm" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="weight_kg">Berat Badan (Kg)</Label>
                                <Input
                                    id="weight_kg"
                                    type="number"
                                    name="weight_kg"
                                    placeholder="Input Berat Badan"
                                    autocomplete="off"
                                    v-model="form.weight_kg"
                                />
                                <InputError :message="form.errors.weight_kg" />
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
                        </div>
                    </CardFooter>
                </Card>
            </form>
        </MainContent>
    </AppLayout>
</template>
