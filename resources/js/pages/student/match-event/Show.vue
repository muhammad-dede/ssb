<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Button } from "@/components/ui/button/index";
import {
    MoreHorizontal,
    Undo2,
    Calendar,
    UserCog,
    Timer,
    MapPin,
    NotebookPen,
    NotebookTabs,
    FileDigit,
    Shirt,
} from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Badge } from "@/components/ui/badge/index";
import { Card, CardContent } from "@/components/ui/card/index";
import InfoItem from "@/components/InfoItem.vue";

const props = defineProps({
    student_match_event: Object,
});

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Pertandingan", href: "/student/match-event" },
    { title: "Detail", href: "/student/match-event/show" },
];
</script>

<template>
    <Head title="Detail Pertandingan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Detail Pertandingan"
                    description="Informasi lengkap mengenai pertandingan yang terdaftar"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="w-8 h-8 p-0">
                            <MoreHorizontal class="w-4 h-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem asChild>
                            <Link :href="route('student.match-event.index')">
                                <Undo2 class="text-yellow-500" />
                                Kembali
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </HeadingGroup>
            <div class="grid lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 h-fit">
                    <Card>
                        <CardContent>
                            <h5 class="text-sm font-bold text-gray-500 mb-4">
                                Informasi Pertandingan
                            </h5>
                            <div class="grid divide-y divide-gray-100">
                                <div class="flex justify-between items-center">
                                    <InfoItem
                                        label="Pelatih"
                                        :value="
                                            student_match_event.match_event
                                                ?.coach?.name ?? '-'
                                        "
                                        :icon="UserCog"
                                        background
                                    />
                                    <Badge
                                        :variant="
                                            student_match_event.match_event
                                                ?.status_variant
                                        "
                                        class="py-2 px-3 rounded-full h-fit"
                                    >
                                        {{
                                            student_match_event.match_event
                                                ?.status_label
                                        }}
                                    </Badge>
                                </div>
                                <InfoItem
                                    label="Tanggal Pelatihan"
                                    :value="
                                        dateFormat(
                                            student_match_event.match_event
                                                ?.match_date
                                        ) ?? '-'
                                    "
                                    :icon="Calendar"
                                    background
                                />
                                <InfoItem
                                    label="Waktu Pelatihan"
                                    :value="
                                        `${student_match_event.match_event?.start_time} - ${student_match_event.match_event?.end_time}` ??
                                        '-'
                                    "
                                    :icon="Timer"
                                    background
                                />
                                <InfoItem
                                    label="Lawan"
                                    :value="
                                        student_match_event.match_event
                                            ?.opponent ?? '-'
                                    "
                                    :icon="Shirt"
                                    background
                                />
                                <InfoItem
                                    label="Skor Tim"
                                    :value="`${
                                        student_match_event.match_event
                                            ?.our_score ?? '-'
                                    }`"
                                    :icon="FileDigit"
                                    background
                                />
                                <InfoItem
                                    label="Skor Lawan"
                                    :value="`${
                                        student_match_event.match_event
                                            ?.opponent_score ?? '-'
                                    }`"
                                    :icon="FileDigit"
                                    background
                                />
                                <InfoItem
                                    label="Lokasi"
                                    :value="
                                        student_match_event.match_event
                                            ?.location ?? '-'
                                    "
                                    :icon="MapPin"
                                    background
                                />
                                <InfoItem
                                    label="Deskripsi"
                                    :value="
                                        student_match_event.match_event
                                            ?.description ?? '-'
                                    "
                                    :icon="NotebookPen"
                                    background
                                />
                            </div>
                        </CardContent>
                    </Card>
                </div>
                <div class="h-fit">
                    <Card>
                        <CardContent>
                            <h5 class="text-sm font-bold text-gray-500 mb-4">
                                Kehadiran & Penilaian
                            </h5>
                            <div class="grid divide-y divide-gray-100">
                                <InfoItem
                                    label="Kehadiran"
                                    :value="
                                        student_match_event.attendance_label
                                    "
                                    :icon="NotebookTabs"
                                    background
                                />
                                <template
                                    v-if="
                                        student_match_event
                                            .student_match_event_assessments
                                            .length > 0
                                    "
                                    v-for="student_assessment in student_match_event.student_match_event_assessments"
                                    :key="student_assessment.id"
                                >
                                    <InfoItem
                                        :label="
                                            student_assessment.assessment?.name
                                        "
                                        :value="`${student_assessment.value}`"
                                        :icon="FileDigit"
                                        background
                                    />
                                </template>
                                <template v-else>
                                    <div
                                        class="flex justify-center items-center w-full pt-6 pb-2 font-semibold text-sm"
                                    >
                                        Belum ada data penilaian
                                    </div>
                                </template>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </MainContent>
    </AppLayout>
</template>
