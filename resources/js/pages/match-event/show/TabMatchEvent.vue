<script setup>
import { Badge } from "@/components/ui/badge/index";
import { Card, CardContent } from "@/components/ui/card/index";
import {
    Calendar,
    Group,
    CalendarDays,
    UserCog,
    Timer,
    MapPin,
    NotebookPen,
    Shirt,
    FileDigit,
} from "lucide-vue-next";
import InfoItem from "@/components/InfoItem.vue";

const props = defineProps({
    variants: Object,
    status_match_events: Object,
    match_event: Object,
});

const getStatusLabel = (status) => {
    if (!status) return "-";
    const found = props.status_match_events?.find(
        (item) => item.value === status
    );
    return found?.label?.toUpperCase() ?? "-";
};

const getVariant = (status) => {
    if (!status) return "outline";
    const found = props.variants?.find((item) => item.value === status);
    return found?.label ?? "outline";
};

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};
</script>

<template>
    <Card>
        <CardContent>
            <h5 class="text-sm font-bold text-gray-500 mb-4">
                Informasi Latihan
            </h5>
            <div class="grid divide-y divide-gray-100">
                <div class="flex justify-between items-center">
                    <InfoItem
                        label="Periode"
                        :value="match_event.period?.name ?? '-'"
                        :icon="CalendarDays"
                        background
                    />
                    <Badge
                        :variant="getVariant(match_event?.status)"
                        class="py-2 px-3 rounded-full h-fit"
                    >
                        {{ getStatusLabel(match_event?.status) }}
                    </Badge>
                </div>
                <InfoItem
                    label="Program"
                    :value="match_event.program?.name ?? '-'"
                    :icon="Group"
                    background
                />
                <InfoItem
                    label="Pelatih"
                    :value="match_event.coach?.name ?? '-'"
                    :icon="UserCog"
                    background
                />
                <InfoItem
                    label="Tanggal Pelatihan"
                    :value="dateFormat(match_event.match_date) ?? '-'"
                    :icon="Calendar"
                    background
                />
                <InfoItem
                    label="Waktu Pelatihan"
                    :value="
                        `${match_event.start_time} - ${match_event.end_time}` ??
                        '-'
                    "
                    :icon="Timer"
                    background
                />
                <InfoItem
                    label="Lawan"
                    :value="match_event.opponent ?? '-'"
                    :icon="Shirt"
                    background
                />
                <InfoItem
                    label="Skor Tim"
                    :value="`${match_event.our_score ?? '-'}`"
                    :icon="FileDigit"
                    background
                />
                <InfoItem
                    label="Skor Lawan"
                    :value="`${match_event.opponent_score ?? '-'}`"
                    :icon="FileDigit"
                    background
                />
                <InfoItem
                    label="Lokasi"
                    :value="match_event.location ?? '-'"
                    :icon="MapPin"
                    background
                />
                <InfoItem
                    label="Deskripsi"
                    :value="match_event.description ?? '-'"
                    :icon="NotebookPen"
                    background
                />
            </div>
        </CardContent>
    </Card>
</template>
