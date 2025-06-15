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
} from "lucide-vue-next";
import InfoItem from "@/components/InfoItem.vue";

const props = defineProps({
    variants: Object,
    status_trainings: Object,
    training: Object,
});

const getStatusLabel = (status) => {
    if (!status) return "-";
    const found = props.status_trainings?.find((item) => item.value === status);
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
                        :value="training.period?.name ?? '-'"
                        :icon="CalendarDays"
                        background
                    />
                    <Badge
                        :variant="getVariant(training?.status)"
                        class="py-2 px-3 rounded-full h-fit"
                    >
                        {{ getStatusLabel(training?.status) }}
                    </Badge>
                </div>
                <InfoItem
                    label="Program"
                    :value="training.program?.name ?? '-'"
                    :icon="Group"
                    background
                />
                <InfoItem
                    label="Pelatih"
                    :value="training.coach?.name ?? '-'"
                    :icon="UserCog"
                    background
                />
                <InfoItem
                    label="Tanggal Pelatihan"
                    :value="dateFormat(training.training_date) ?? '-'"
                    :icon="Calendar"
                    background
                />
                <InfoItem
                    label="Waktu Pelatihan"
                    :value="
                        `${training.start_time} - ${training.end_time}` ?? '-'
                    "
                    :icon="Timer"
                    background
                />
                <InfoItem
                    label="Lokasi"
                    :value="training.location ?? '-'"
                    :icon="MapPin"
                    background
                />
                <InfoItem
                    label="Deskripsi"
                    :value="training.description ?? '-'"
                    :icon="NotebookPen"
                    background
                />
            </div>
        </CardContent>
    </Card>
</template>
