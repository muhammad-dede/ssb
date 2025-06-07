<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Card, CardContent, CardFooter } from "@/components/ui/card/index";
import { Label } from "@/components/ui/label/index";
import { Input } from "@/components/ui/input/index";
import { Checkbox } from "@/components/ui/checkbox/index";
import InputError from "@/components/InputError.vue";
import { LoaderCircle } from "lucide-vue-next";
import { Button, buttonVariants } from "@/components/ui/button/index";
import { ref, watch } from "vue";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import usePermissions from "@/composables/usePermissions";

const { can } = usePermissions();

const props = defineProps({
    permissions: Object,
    role: Object,
});

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Role", href: "/role" },
    { title: "Ubah", href: "/role/edit" },
];

const form = useForm({
    name: props.role?.name ?? "",
    permissions: props.role?.permissions?.map((p) => p.id) || [],
});

const permissionStates = ref(
    Object.fromEntries((props.role?.permissions || []).map((p) => [p.id, true]))
);

watch(
    permissionStates,
    (newStates) => {
        form.permissions = [];
        for (const [id, isChecked] of Object.entries(newStates)) {
            if (isChecked) {
                form.permissions.push(parseInt(id));
            }
        }
    },
    { deep: true }
);

const handleCheckboxChange = (permissionId, checked) => {
    permissionStates.value[permissionId] = checked;
};

const submit = () => {
    form.patch(route("role.update", props.role?.id));
};
</script>

<template>
    <Head title="Ubah Role" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Ubah Role"
                    description="Formulir untuk mengubah data role yang telah terdaftar"
                />
            </HeadingGroup>
            <form @submit.prevent="submit">
                <Card>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-x-2 gap-y-6 mb-4"
                        >
                            <div class="w-full flex flex-col gap-2">
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
                        </div>
                        <div class="grid gap-2 mb-4">
                            <span
                                class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
                                >Permission</span
                            >
                            <div class="grid grid-cols-4 gap-4">
                                <div
                                    v-for="permission in permissions"
                                    :key="permission.id"
                                    class="flex items-center space-x-2"
                                >
                                    <Checkbox
                                        :id="`permission-${permission.id}`"
                                        v-model="
                                            permissionStates[permission.id]
                                        "
                                        @update:modelValue="
                                            (checked) =>
                                                handleCheckboxChange(
                                                    permission.id,
                                                    checked
                                                )
                                        "
                                    />
                                    <Label
                                        :for="`permission-${permission.id}`"
                                        class="cursor-pointer"
                                    >
                                        {{ permission.name }}
                                    </Label>
                                </div>
                            </div>
                            <InputError :message="form.errors.permissions" />
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
                                v-if="can('role.index')"
                                :href="route('role.index')"
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
