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
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import InputError from "@/components/InputError.vue";
import LabelSpan from "@/components/LabelSpan.vue";

const props = defineProps({
    label: String,
    bank_accounts: Object,
    payment_methods: Object,
    banks: Object,
    status_payments: Object,
    student_program: Object,
});

const isDialogOpen = ref(false);

const getDefaultDueDate = () => {
    const date = new Date();
    date.setDate(date.getDate());
    return date.toISOString().split("T")[0];
};

const getAmount = () => {
    return (
        props.student_program?.billing?.payment?.amount ??
        props.student_program?.billing?.amount ??
        0
    );
};

const receiverId = (receiver_bank_code, receiver_account_number) => {
    if (!receiver_bank_code || !receiver_account_number) return "";
    const found = props.bank_accounts?.find(
        (item) =>
            item.bank_code === receiver_bank_code &&
            item.account_number === receiver_account_number
    );
    return found?.id ?? "";
};

const form = useForm({
    amount: getAmount(),
    payment_date:
        props.student_program?.billing?.payment?.payment_date ??
        getDefaultDueDate(),
    method: props.student_program?.billing?.payment?.method ?? "",
    receiver_id: receiverId(
        props.student_program?.billing?.payment?.receiver_bank_code,
        props.student_program?.billing?.payment?.receiver_account_number
    ),
    sender_bank_code:
        props.student_program?.billing?.payment?.sender_bank_code ?? "",
    sender_account_number:
        props.student_program?.billing?.payment?.sender_account_number ?? "",
    sender_account_holder_name:
        props.student_program?.billing?.payment?.sender_account_holder_name ??
        "",
    proof_file: null,
    reference_number:
        props.student_program?.billing?.payment?.reference_number ?? "",
    notes: props.student_program?.billing?.payment?.notes ?? "",
    status: props.student_program?.billing?.payment?.status ?? "",
});

const handleFileChange = (event) => {
    form.proof_file = event.target.files[0];
};

const submit = () => {
    form.post(route("student-program.payment", props.student_program?.id), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            isDialogOpen.value = false;
        },
    });
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button type="button" class="w-full">{{ label }}</Button>
        </DialogTrigger>
        <DialogScrollContent class="max-w-[80%] md:max-w-[40%]">
            <DialogHeader>
                <DialogTitle>Pembayaran</DialogTitle>
                <DialogDescription>
                    Lakukan pembayaran tagihan registrasi siswa.
                </DialogDescription>
            </DialogHeader>
            <div class="grid">
                <div class="flex flex-col gap-2 pb-4">
                    <Label for="amount">Total Bayar</Label>
                    <Input
                        id="amount"
                        type="number"
                        name="amount"
                        placeholder="Input Total Bayar"
                        autocomplete="off"
                        v-model="form.amount"
                        readonly
                    />
                    <InputError :message="form.errors.amount" />
                </div>
                <div class="flex flex-col gap-2 py-4">
                    <Label for="payment_date">Tanggal Bayar</Label>
                    <Input
                        id="payment_date"
                        type="date"
                        name="payment_date"
                        v-model="form.payment_date"
                    />
                    <InputError :message="form.errors.payment_date" />
                </div>
                <div class="flex flex-col gap-2 py-4">
                    <LabelSpan label="Metode Pembayaran" />
                    <RadioGroup :orientation="'vertical'" v-model="form.method">
                        <div
                            class="flex items-center space-x-2"
                            v-for="(method, index) in payment_methods"
                            :key="index"
                        >
                            <RadioGroupItem
                                :id="`method-${index}`"
                                :value="method.value"
                            />
                            <Label :for="`method-${index}`">
                                {{ method.label }}
                            </Label>
                        </div>
                    </RadioGroup>
                    <InputError :message="form.errors.method" />
                </div>
                <template v-if="form.method === 'TRANSFER'">
                    <div class="flex flex-col gap-2 py-4">
                        <Label for="receiver_id">Bank Tujuan</Label>
                        <Select v-model="form.receiver_id" name="receiver_id">
                            <SelectTrigger id="receiver_id" class="w-full">
                                <SelectValue placeholder="Pilih Bank" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(item, index) in bank_accounts"
                                        :key="index"
                                        :value="item.id"
                                    >
                                        {{ item.bank?.name }}
                                        -
                                        {{ item.account_number }}
                                        a.n
                                        {{ item.account_holder_name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.receiver_id" />
                    </div>
                    <div class="flex flex-col gap-2 py-4">
                        <Label for="sender_bank_code">Bank Pengirim</Label>
                        <Select
                            v-model="form.sender_bank_code"
                            name="sender_bank_code"
                        >
                            <SelectTrigger id="sender_bank_code" class="w-full">
                                <SelectValue placeholder="Pilih Bank" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(item, index) in banks"
                                        :key="index"
                                        :value="item.code"
                                    >
                                        {{ item.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.sender_bank_code" />
                    </div>
                    <div class="flex flex-col gap-2 py-4">
                        <Label for="sender_account_number"
                            >No Rekening Pengirim</Label
                        >
                        <Input
                            id="sender_account_number"
                            type="text"
                            name="sender_account_number"
                            placeholder="Input No Rekening Pengirim"
                            autocomplete="off"
                            v-model="form.sender_account_number"
                        />
                        <InputError
                            :message="form.errors.sender_account_number"
                        />
                    </div>
                    <div class="flex flex-col gap-2 py-4">
                        <Label for="sender_account_holder_name"
                            >Atas Nama Pengirim</Label
                        >
                        <Input
                            id="sender_account_holder_name"
                            type="text"
                            name="sender_account_holder_name"
                            placeholder="Input Atas Nama Pengirim"
                            autocomplete="off"
                            v-model="form.sender_account_holder_name"
                        />
                        <InputError
                            :message="form.errors.sender_account_holder_name"
                        />
                    </div>
                    <div class="flex flex-col gap-2 py-4">
                        <Label for="proof_file">Bukti Transfer</Label>
                        <Input
                            id="proof_file"
                            type="file"
                            name="proof_file"
                            accept="image/*"
                            @change="handleFileChange"
                        />
                        <InputError :message="form.errors.proof_file" />
                    </div>
                    <div class="flex flex-col gap-2 py-4">
                        <Label for="reference_number">No Referensi</Label>
                        <Input
                            id="reference_number"
                            type="text"
                            name="reference_number"
                            placeholder="Input No Referensi"
                            autocomplete="off"
                            v-model="form.reference_number"
                        />
                        <InputError :message="form.errors.reference_number" />
                    </div>
                </template>
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
                <div class="flex flex-col gap-2 py-4">
                    <Label for="status">Status Pembayaran</Label>
                    <Select v-model="form.status" name="status">
                        <SelectTrigger id="status" class="w-full">
                            <SelectValue placeholder="Pilih Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem
                                    v-for="(item, index) in status_payments"
                                    :key="index"
                                    :value="item.value"
                                >
                                    {{ item.label }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.status" />
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
                    {{ label }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
