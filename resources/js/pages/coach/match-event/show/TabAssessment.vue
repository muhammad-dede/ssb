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
import { LoaderCircle } from "lucide-vue-next";

const props = defineProps({
    match_event: Object,
    assessments: Object,
    student_match_events: Object,
});

const isEdit = ref(false);

const form = useForm({
    assessments: [],
});

watch(
    () => props.student_match_events,
    (studentMatchEvents) => {
        form.assessments = (studentMatchEvents ?? []).flatMap((st) => {
            return (st.student_match_event_assessments ?? []).map((a) => ({
                id: a.id,
                student_match_event_id: st.id,
                assessment_code: a.assessment_code,
                value: a.value ?? 0,
            }));
        });
    },
    { immediate: true }
);

const resetAssessments = () => {
    const studentMatchEvents = props.student_match_events ?? [];
    form.assessments = studentMatchEvents.flatMap((st) => {
        return (st.student_match_event_assessments ?? []).map((a) => ({
            id: a.id,
            student_match_event_id: st.id,
            assessment_code: a.assessment_code,
            value: a.value ?? 0,
        }));
    });
};

const getAssessmentValue = (studentMatchEventId, assessmentCode) => {
    const found = form.assessments.find(
        (a) =>
            a.student_match_event_id === studentMatchEventId &&
            a.assessment_code === assessmentCode
    );
    return found?.value ?? 0;
};

const updateAssessmentValue = (studentMatchEventId, assessmentCode, value) => {
    const found = form.assessments.find(
        (a) =>
            a.student_match_event_id === studentMatchEventId &&
            a.assessment_code === assessmentCode
    );
    if (found) {
        found.value = Number(value);
    }
};

const submit = () => {
    form.post(route("coach.match-event.assessment"), {
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
                        <template v-for="item in assessments" :key="item.code">
                            <TableHead class="w-[5%]">{{
                                item.name
                            }}</TableHead>
                        </template>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="student_match_events.length > 0">
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
                                v-for="assessment in assessments"
                                :key="assessment.code"
                            >
                                <TableCell>
                                    <Input
                                        type="number"
                                        min="10"
                                        max="100"
                                        :readonly="!isEdit"
                                        class="border-none shadow-none"
                                        :model-value="
                                            getAssessmentValue(
                                                student_match_event.id,
                                                assessment.code
                                            )
                                        "
                                        @update:model-value="
                                            (value) =>
                                                updateAssessmentValue(
                                                    student_match_event.id,
                                                    assessment.code,
                                                    value
                                                )
                                        "
                                        :name="`assessment[${student_match_event.id}][${assessment.code}]`"
                                    />
                                </TableCell>
                            </template>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell
                                :colspan="3 + assessments.length"
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
            v-if="student_match_events.length > 0"
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
                        resetAssessments();
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
