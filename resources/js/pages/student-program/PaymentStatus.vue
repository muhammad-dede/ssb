<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button/index";
import { LoaderCircle } from "lucide-vue-next";
import {
    Dialog,
    DialogScrollContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import InputError from "@/components/InputError.vue";
import LabelSpan from "@/components/LabelSpan.vue";

const props = defineProps({
    bank_accounts: Object,
    payment_methods: Object,
    banks: Object,
    status_payments: Object,
    student_program: Object,
});

const form = useForm({
    notes: props.student_program?.billing?.payment?.notes ?? "",
    status: props.student_program?.billing?.payment?.status ?? "",
});

const isDialogOpen = ref(false);

const submit = () => {
    form.post(
        route("student-program.payment.status", props.student_program?.id),
        {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                isDialogOpen.value = false;
            },
        }
    );
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button type="button" class="w-full">Konfirmasi Pembayaran</Button>
        </DialogTrigger>
        <DialogScrollContent class="max-w-[80%] md:max-w-[40%]">
            <DialogHeader>
                <DialogTitle>Konfirmasi Pembayaran</DialogTitle>
                <DialogDescription>
                    Lakukan konfirmasi pembayaran tagihan registrasi siswa.
                </DialogDescription>
            </DialogHeader>
            <div class="grid">
                <div class="flex flex-col gap-2 py-4">
                    <LabelSpan label="Pilih Status" />
                    <RadioGroup :orientation="'vertical'" v-model="form.status">
                        <div
                            class="flex items-center space-x-2"
                            v-for="(item, index) in status_payments"
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
                <div class="flex flex-col gap-2 py-4">
                    <Label for="notes">Catatan</Label>
                    <Input
                        id="notes"
                        type="text"
                        name="notes"
                        placeholder="Input Catatan"
                        autocomplete="off"
                        v-model="form.notes"
                    />
                    <InputError :message="form.errors.notes" />
                </div>
            </div>
            <DialogFooter>
                <Button
                    type="submit"
                    :disabled="form.processing"
                    @click.prevent="submit"
                >
                    <LoaderCircle
                        v-if="form.processing"
                        class="h-4 w-4 animate-spin"
                    />
                    Kirim
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
