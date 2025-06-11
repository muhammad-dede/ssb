<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import AppLayout from "@/layouts/AppLayout.vue";
import MainContent from "@/components/MainContent.vue";
import PaginationLinks from "@/components/PaginationLinks.vue";
import { Button, buttonVariants } from "@/components/ui/button/index";
import { SquarePlus, MoreHorizontal } from "lucide-vue-next";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table/index";
import { Badge } from "@/components/ui/badge/index";
import SearchInput from "@/components/SearchInput.vue";
import FilterControl from "@/components/FilterControl.vue";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import usePermissions from "@/composables/usePermissions";

const { can, canAny } = usePermissions();

const props = defineProps({
    variants: Object,
    status_bank_accounts: Object,
    bank_accounts: Object,
    search_term: String,
    per_page_term: String,
    filter_term: String,
});

const search = ref(props.search_term);
const perPage = ref(props.per_page_term);
const filter = ref(props.filter_term);
const bankAccountToDelete = ref(null);

const dataControl = () => {
    router.get(
        route("bank-account.index"),
        {
            search: search.value,
            per_page: perPage.value,
            filter: filter.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};
watch(
    search,
    debounce(() => {
        dataControl();
    }, 1000)
);
watch([perPage, filter], () => {
    dataControl();
});

const confirmDelete = (bankAccount) => {
    bankAccountToDelete.value = bankAccount;
};

const destroy = () => {
    if (!bankAccountToDelete.value) return;
    const bankAccountId = bankAccountToDelete.value.id;
    router.delete(route("bank-account.destroy", bankAccountId), {
        preserveScroll: true,
        onFinish: () => {
            bankAccountToDelete.value = null;
        },
    });
};

const getStatusLabel = (status) => {
    if (!status) return "-";
    const found = props.status_bank_accounts?.find(
        (item) => item.value === status
    );
    return found?.label?.toUpperCase() ?? "-";
};

const getStatusVariant = (status) => {
    if (!status) return "outline";
    const found = props.variants?.find((item) => item.value === status);
    return found?.label ?? "outline";
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Akun Bank", href: "/bank-account" },
];
</script>

<template>
    <Head title="Akun Bank" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Data Akun Bank"
                    description="Lihat dan kelola data akun bank yang tersedia"
                />
                <Link
                    v-if="can('bank-account.create')"
                    :href="route('bank-account.create')"
                    :class="buttonVariants({ variant: 'default' })"
                >
                    <SquarePlus class="w-4 h-4" />Tambah
                </Link>
            </HeadingGroup>
            <div class="flex justify-between items-center gap-4 mb-4">
                <SearchInput v-model="search" />
                <FilterControl
                    :per-page="perPage"
                    :filter="filter"
                    @update:per-page="perPage = $event"
                    @update:filter="filter = $event"
                />
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[10px]">No</TableHead>
                            <TableHead>Bank</TableHead>
                            <TableHead>Nomor Rekening</TableHead>
                            <TableHead>Atas Nama Pemilik</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="w-[10px]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="bank_accounts.data.length > 0">
                            <TableRow
                                v-for="(item, index) in bank_accounts.data"
                                :key="item.id"
                            >
                                <TableCell class="font-medium">
                                    {{ bank_accounts.from + index }}
                                </TableCell>
                                <TableCell>
                                    {{ item.bank?.name ?? "-" }}
                                </TableCell>
                                <TableCell>
                                    {{ item.account_number }}
                                </TableCell>
                                <TableCell>
                                    {{ item.account_holder_name }}
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            getStatusVariant(item?.status)
                                        "
                                    >
                                        {{ getStatusLabel(item?.status) }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-center">
                                    <DropdownMenu
                                        v-if="
                                            canAny(
                                                'bank-account.edit',
                                                'bank-account.delete'
                                            )
                                        "
                                    >
                                        <DropdownMenuTrigger as-child>
                                            <Button
                                                variant="outline"
                                                class="w-8 h-8 p-0"
                                            >
                                                <MoreHorizontal
                                                    class="w-4 h-4"
                                                />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuLabel>
                                                Aksi
                                            </DropdownMenuLabel>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem
                                                asChild
                                                v-if="can('bank-account.edit')"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'bank-account.edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    Ubah
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                v-if="
                                                    can('bank-account.delete')
                                                "
                                                @select="
                                                    () => confirmDelete(item)
                                                "
                                            >
                                                Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow>
                                <TableCell colspan="6" class="text-center py-6">
                                    <strong>Tidak ada data</strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
            <PaginationLinks :paginator="bank_accounts" />
        </MainContent>
    </AppLayout>
    <AlertDialog :open="!!bankAccountToDelete">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>
                    Apakah Anda benar-benar yakin?
                </AlertDialogTitle>
                <AlertDialogDescription>
                    Tindakan ini tidak dapat dibatalkan. Ini akan secara
                    permanen menghapus data terkait dari server kami.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="bankAccountToDelete = null">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="destroy">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
