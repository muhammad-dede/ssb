<script setup>
import { ref, watch, computed } from "vue";
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
    assessments: Object,
    training: Object,
    training_assessments: Object,
});

const isEdit = ref(false);

const form = useForm({
    assessments: [],
});

watch(
    () => props.training_assessments,
    (newVal) => {
        form.assessments = (newVal ?? []).map((item) => ({
            student_id: item.student_id ?? null,
            assessment_code: item.assessment_code ?? null,
            value: item.value ?? 0,
        }));
    },
    { immediate: true, deep: true }
);

const resetAssessments = () => {
    form.assessments = (props.training_assessments ?? []).map((item) => ({
        student_id: item.student_id ?? null,
        assessment_code: item.assessment_code ?? null,
        value: item.value ?? 0,
    }));
};

const groupedTrainingAssessments = computed(() => {
    if (!props.training_assessments?.length) return [];
    const grouped = {};
    props.training_assessments.forEach((item) => {
        const studentId = item.student_id;
        if (!grouped[studentId]) {
            grouped[studentId] = {
                student: item.student,
                student_id: studentId,
                assessments: {},
            };
        }
        grouped[studentId].assessments[item.assessment_code] = item.value || 0;
    });
    return Object.values(grouped);
});

const getAssessmentValue = (studentId, assessmentCode) => {
    const formData = form.assessments.find(
        (item) =>
            item.student_id === studentId &&
            item.assessment_code === assessmentCode
    );
    return formData?.value ?? 0;
};

const updateAssessmentValue = (studentId, assessmentCode, value) => {
    const numValue = value === "" ? 0 : parseInt(value) || 0;
    const existingIndex = form.assessments.findIndex(
        (item) =>
            item.student_id === studentId &&
            item.assessment_code === assessmentCode
    );
    if (existingIndex !== -1) {
        form.assessments[existingIndex].value = numValue;
    } else {
        form.assessments.push({
            student_id: studentId,
            assessment_code: assessmentCode,
            value: numValue,
        });
    }
};

const submit = () => {
    form.post(route("training.assessment", props.training?.id), {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            isEdit.value = false;
        },
    });
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
                        <template v-for="item in assessments" :key="item.code">
                            <TableHead class="w-[5%]">{{
                                item.name
                            }}</TableHead>
                        </template>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="groupedTrainingAssessments.length > 0">
                        <TableRow
                            v-for="(
                                student_assessment, index
                            ) in groupedTrainingAssessments"
                            :key="student_assessment.student_id"
                        >
                            <TableCell class="font-medium">
                                {{ index + 1 }}
                            </TableCell>
                            <TableCell class="font-semibold">
                                {{ student_assessment.student?.name ?? "-" }}
                            </TableCell>
                            <template
                                v-for="assessment in assessments"
                                :key="assessment.code"
                            >
                                <TableCell>
                                    <Input
                                        type="number"
                                        :model-value="
                                            getAssessmentValue(
                                                student_assessment.student_id,
                                                assessment.code
                                            )
                                        "
                                        @update:model-value="
                                            (value) =>
                                                updateAssessmentValue(
                                                    student_assessment.student_id,
                                                    assessment.code,
                                                    value
                                                )
                                        "
                                        :name="`assessments[${student_assessment.student_id}][${assessment.code}]`"
                                        class="border-none shadow-none"
                                        min="0"
                                        :readonly="!isEdit"
                                    />
                                </TableCell>
                            </template>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell
                                :colspan="2 + assessments.length"
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
            v-if="groupedTrainingAssessments.length > 0"
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
