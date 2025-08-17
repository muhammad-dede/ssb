<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table/index";
import { Button } from "@/components/ui/button/index";
import { Input } from "@/components/ui/input/index";
import { CircleCheck, Circle, LoaderCircle } from "lucide-vue-next";

const props = defineProps({
    match_event: Object,
    attendances: Object,
    student_match_events: Object,
});

const isEdit = ref(false);

const form = useForm({
    attendances: [],
});

watch(
    () => props.student_match_events,
    (newVal) => {
        form.attendances = (newVal ?? []).map((item) => ({
            id: item.id ?? null,
            student_id: item.student_id ?? null,
            match_event_id: item.match_event_id ?? null,
            attendance: item.attendance ?? null,
            notes: item.notes ?? null,
        }));
    },
    { immediate: true }
);

const resetAttendance = () => {
    form.attendances = (props.student_match_events ?? []).map((item) => ({
        id: item.id ?? null,
        student_id: item.student_id ?? null,
        match_event_id: item.match_event_id ?? null,
        attendance: item.attendance ?? null,
        notes: item.notes ?? null,
    }));
};

const submit = () => {
    form.post(route("coach.match-event.attendance"), {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            isEdit.value = false;
        },
    });
};

const dateFormat = (date) => {
    if (!date) return "-";
    const options = { day: "numeric", month: "long", year: "numeric" };
    return new Date(date).toLocaleDateString("id-ID", options);
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="rounded-md border mb-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[10px]">No</TableHead>
                        <TableHead>Siswa</TableHead>
                        <TableHead>Tanggal</TableHead>
                        <template v-for="item in attendances" :key="item.value">
                            <TableHead class="w-[5%]">{{
                                item.label
                            }}</TableHead>
                        </template>
                        <TableHead>Keterangan</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="student_match_events?.length > 0">
                        <TableRow
                            v-for="(
                                student_match_event, index
                            ) in student_match_events"
                            :key="student_match_event.id"
                        >
                            <TableCell class="font-medium">
                                {{ index + 1 }}
                            </TableCell>
                            <TableCell class="font-semibold">
                                {{ student_match_event.student?.name ?? "-" }}
                            </TableCell>
                            <TableCell>
                                {{ dateFormat(match_event.match_date) ?? "-" }}
                            </TableCell>
                            <template
                                v-for="attendance in attendances"
                                :key="attendance.value"
                            >
                                <TableCell>
                                    <div
                                        :class="[
                                            'flex justify-center items-center',
                                            isEdit ? 'cursor-pointer' : '',
                                        ]"
                                        @click="
                                            isEdit
                                                ? (form.attendances[
                                                      index
                                                  ].attendance =
                                                      attendance.value)
                                                : null
                                        "
                                    >
                                        <CircleCheck
                                            v-if="
                                                form.attendances[index]
                                                    .attendance ===
                                                attendance.value
                                            "
                                            class="size-5 text-orange-500"
                                        />
                                        <Circle
                                            v-else
                                            class="size-5 text-gray-400 hover:text-orange-500 transition-colors"
                                        />
                                    </div>
                                </TableCell>
                            </template>
                            <TableCell class="w-[20%]">
                                <Input
                                    class="border-none shadow-none"
                                    type="text"
                                    :name="`attendance_${index}_notes`"
                                    autocomplete="off"
                                    v-model="form.attendances[index].notes"
                                    :readonly="!isEdit"
                                />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell
                                :colspan="4 + attendances.length"
                                class="text-center py-6"
                            >
                                <strong>Belum ada data</strong>
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>
        <div
            v-if="student_match_events?.length > 0"
            class="flex justify-end items-center"
        >
            <div v-if="isEdit" class="space-x-2">
                <Button type="submit" :disabled="form.processing">
                    <LoaderCircle
                        v-if="form.processing"
                        class="h-4 w-4 animate-spin"
                    />
                    Simpan
                </Button>
                <Button
                    type="button"
                    variant="outline"
                    @click="
                        isEdit = false;
                        resetAttendance();
                    "
                >
                    Batal
                </Button>
            </div>
            <Button
                v-else
                type="button"
                variant="secondary"
                @click="isEdit = true"
            >
                Ubah
            </Button>
        </div>
    </form>
</template>
