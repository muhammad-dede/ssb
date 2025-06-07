<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";

import AppLayout from "@/layouts/AppLayout.vue";
import MainContent from "@/components/MainContent.vue";
import PaginationLinks from "@/components/PaginationLinks.vue";

import {
    Card,
    CardDescription,
    CardHeader,
    CardTitle,
    CardContent,
} from "@/components/ui/card/index";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table/index";

import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from "@/components/ui/alert-dialog";

import { Button, buttonVariants } from "@/components/ui/button/index";
import { Input } from "@/components/ui/input/index";
import { Badge } from "@/components/ui/badge/index";
import { MoreHorizontal } from "lucide-vue-next";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

const props = defineProps({
    roles: Object,
    search_term: String,
});

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Role", href: "/role" },
];

const search = ref(props.search_term);
const isDeleteDialogOpen = ref(false);
const itemToDelete = ref(null);

watch(
    search,
    debounce(
        (query) =>
            router.get(
                route("role.index"),
                { search: query },
                { preserveState: true }
            ),
        2000
    )
);

const handleEdit = (id) => {
    router.get(route("role.edit", id));
};

const openDeleteDialog = (id) => {
    itemToDelete.value = id;
    isDeleteDialogOpen.value = true;
};

const closeDeleteDialog = () => {
    isDeleteDialogOpen.value = false;
    itemToDelete.value = null;
};

const confirmDelete = () => {
    handleDelete(itemToDelete.value);
    closeDeleteDialog();
};

const handleDelete = (id) => {
    router.delete(route("role.destroy", id), { preserveScroll: true });
};
</script>
<template>
    <Head title="Role" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <Card>
                <CardHeader>
                    <CardTitle>Data Role</CardTitle>
                    <CardDescription
                        >Kelola Data Role Untuk Akses Pengguna</CardDescription
                    >
                </CardHeader>
                <CardContent>
                    <div class="flex items-center py-4 space-x-2">
                        <Input
                            class="max-w-sm"
                            placeholder="Cari..."
                            name="search"
                            type="search"
                            v-model="search"
                        />
                        <Link
                            :href="route('role.create')"
                            :class="buttonVariants({ variant: 'default' })"
                            >Tambah</Link
                        >
                    </div>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[10px]">No</TableHead>
                                    <TableHead>Nama</TableHead>
                                    <TableHead>Permission</TableHead>
                                    <TableHead class="w-[10px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="roles.data.length > 0">
                                    <TableRow
                                        v-for="(item, index) in roles.data"
                                        :key="item.id"
                                    >
                                        <TableCell class="font-medium">
                                            {{ roles.from + index }}
                                        </TableCell>
                                        <TableCell>
                                            {{ item.name }}
                                        </TableCell>
                                        <TableCell>
                                            <Badge variant="secondary">
                                                {{ item.permissions_count }}
                                                Permission
                                            </Badge>
                                        </TableCell>
                                        <TableCell
                                            class="text-center space-x-2"
                                        >
                                            <DropdownMenu>
                                                <DropdownMenuTrigger as-child>
                                                    <Button
                                                        variant="ghost"
                                                        class="w-8 h-8 p-0"
                                                    >
                                                        <MoreHorizontal
                                                            class="w-4 h-4"
                                                        />
                                                    </Button>
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent
                                                    align="end"
                                                >
                                                    <DropdownMenuLabel>
                                                        Aksi
                                                    </DropdownMenuLabel>
                                                    <DropdownMenuSeparator />
                                                    <DropdownMenuItem
                                                        @click="
                                                            handleEdit(item.id)
                                                        "
                                                    >
                                                        Edit
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem
                                                        @click="
                                                            openDeleteDialog(
                                                                item.id
                                                            )
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
                                        <TableCell
                                            colspan="4"
                                            class="text-center py-6"
                                        >
                                            <strong v-if="search">
                                                Tidak ada data ditemukan
                                            </strong>
                                            <strong v-else>
                                                Belum ada data
                                            </strong>
                                        </TableCell>
                                    </TableRow>
                                </template>
                            </TableBody>
                        </Table>
                    </div>
                    <PaginationLinks :paginator="roles" />
                </CardContent>
            </Card>
        </MainContent>

        <AlertDialog
            :open="isDeleteDialogOpen"
            @update:open="isDeleteDialogOpen = $event"
        >
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
                    <AlertDialogCancel @click="closeDeleteDialog">
                        Batal
                    </AlertDialogCancel>
                    <AlertDialogAction @click="confirmDelete">
                        Hapus
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>
