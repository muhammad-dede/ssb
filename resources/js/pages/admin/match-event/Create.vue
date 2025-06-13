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
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";
import LabelSpan from "@/components/LabelSpan.vue";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import usePermissions from "@/composables/usePermissions";

const { can } = usePermissions();

const props = defineProps({
    status_match_events: Object,
    periods: Object,
    programs: Object,
    coaches: Object,
});

const getDefaultDueDate = () => {
    const date = new Date();
    date.setDate(date.getDate());
    return date.toISOString().split("T")[0];
};

const form = useForm({
    period_id: "",
    program_code: "",
    coach_id: "",
    match_date: getDefaultDueDate(),
    start_time: "",
    end_time: "",
    opponent: "",
    our_score: 0,
    opponent_score: 0,
    location: "",
    description: "",
    status: "",
});

const submit = () => {
    form.post(route("admin.match-event.store"), {
        preserveScroll: true,
    });
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Pelatihan", href: "/admin/match-event" },
    { title: "Tambah", href: "/admin/match-event/create" },
];
</script>

<template>
    <Head title="Tambah Pelatihan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Tambah Pelatihan"
                    description="Formulir untuk menambahkan data pelatihan baru"
                />
            </HeadingGroup>
            <form @submit.prevent="submit">
                <Card>
                    <CardContent>
                        <Heading title="Informasi Pelatihan" />
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-x-2 gap-y-6 my-4"
                        >
                            <div class="w-full flex flex-col gap-2">
                                <Label for="period_id">Periode</Label>
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
                                                v-for="(item, index) in periods"
                                                :key="index"
                                                :value="item.id"
                                            >
                                                {{ item.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.period_id" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="program_code">Program</Label>
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
                            <div class="w-full flex flex-col gap-2">
                                <Label for="coach_id">Pelatih</Label>
                                <Select v-model="form.coach_id" name="coach_id">
                                    <SelectTrigger id="coach_id" class="w-full">
                                        <SelectValue
                                            placeholder="Pilih Pelatih"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="(item, index) in coaches"
                                                :key="index"
                                                :value="item.id"
                                            >
                                                {{ item.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.coach_id" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="match_date"
                                    >Tanggal Pertandingan</Label
                                >
                                <Input
                                    id="match_date"
                                    type="date"
                                    name="match_date"
                                    v-model="form.match_date"
                                />
                                <InputError :message="form.errors.match_date" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="start_time">Waktu Mulai</Label>
                                <Input
                                    id="start_time"
                                    type="time"
                                    name="start_time"
                                    v-model="form.start_time"
                                />
                                <InputError :message="form.errors.start_time" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="end_time">Waktu Selesai</Label>
                                <Input
                                    id="end_time"
                                    type="time"
                                    name="end_time"
                                    v-model="form.end_time"
                                />
                                <InputError :message="form.errors.end_time" />
                            </div>
                            <div
                                class="w-full flex flex-col gap-2 md:col-span-2"
                            >
                                <Label for="opponent">Lawan</Label>
                                <Input
                                    id="opponent"
                                    type="text"
                                    name="opponent"
                                    placeholder="Input Lawan"
                                    autocomplete="off"
                                    v-model="form.opponent"
                                />
                                <InputError :message="form.errors.opponent" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="our_score">Skor Tim</Label>
                                <Input
                                    id="our_score"
                                    type="number"
                                    name="our_score"
                                    placeholder="Input Skor Tim"
                                    v-model="form.our_score"
                                />
                                <InputError :message="form.errors.our_score" />
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <Label for="opponent_score">Skor Lawan</Label>
                                <Input
                                    id="opponent_score"
                                    type="number"
                                    name="opponent_score"
                                    placeholder="Input Skor Lawan"
                                    v-model="form.opponent_score"
                                />
                                <InputError
                                    :message="form.errors.opponent_score"
                                />
                            </div>
                            <div
                                class="w-full flex flex-col gap-2 md:col-span-2"
                            >
                                <Label for="location"
                                    >Lokasi Pertandingan</Label
                                >
                                <Input
                                    id="location"
                                    type="text"
                                    name="location"
                                    placeholder="Input Lokasi Latihan"
                                    autocomplete="off"
                                    v-model="form.location"
                                />
                                <InputError :message="form.errors.location" />
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
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <LabelSpan label="Pilih Status" />
                                <RadioGroup
                                    :orientation="'vertical'"
                                    v-model="form.status"
                                >
                                    <div
                                        class="flex items-center space-x-2"
                                        v-for="(
                                            item, index
                                        ) in status_match_events"
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
                                v-if="can('admin.match-event.index')"
                                :href="route('admin.match-event.index')"
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
