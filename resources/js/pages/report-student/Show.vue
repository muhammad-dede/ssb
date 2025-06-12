<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Card, CardContent } from "@/components/ui/card/index";
import { Button } from "@/components/ui/button/index";
import {
    MoreHorizontal,
    Undo2,
    PersonStanding,
    BookOpenCheck,
    Bookmark,
} from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import InfoItem from "@/components/InfoItem.vue";
import usePermissions from "@/composables/usePermissions";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table/index";

const { can } = usePermissions();

const props = defineProps({
    student_program: Object,
});

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Raport Siswa", href: "/report-student" },
    { title: "Detail", href: "/report-student/show" },
];
</script>

<template>
    <Head title="Detail Raport Siswa" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Detail Raport Siswa"
                    description="Informasi lengkap mengenai raport siswa yang terdaftar"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="w-8 h-8 p-0">
                            <MoreHorizontal class="w-4 h-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem
                            v-if="can('report-student.index')"
                            asChild
                        >
                            <Link :href="route('report-student.index')">
                                <Undo2 class="text-yellow-500" />
                                Kembali
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </HeadingGroup>
            <Card class="h-fit w-full py-3 mb-4">
                <CardContent>
                    <h5 class="text-sm font-bold text-gray-500 mb-4">
                        Informasi Raport Siswa
                    </h5>
                    <div
                        class="grid divide-y divide-gray-100 lg:grid-cols-3 lg:divide-y-0"
                    >
                        <InfoItem
                            label="Siswa"
                            :value="student_program.student?.name ?? '-'"
                            :icon="PersonStanding"
                            background
                        />
                        <InfoItem
                            label="Program"
                            :value="student_program.program?.name ?? '-'"
                            :icon="BookOpenCheck"
                            background
                        />
                        <InfoItem
                            label="Periode"
                            :value="student_program.period?.name ?? '-'"
                            :icon="Bookmark"
                            background
                        />
                    </div>
                </CardContent>
            </Card>
            <div class="rounded-md border mb-4">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[10px]">NO</TableHead>
                            <TableHead>LATIHAN</TableHead>
                            <TableHead class="w-[10px]">NILAI</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="(score, index) in student_program.report
                                ?.training?.scores"
                            :key="index"
                        >
                            <TableCell class="font-medium">{{
                                index + 1
                            }}</TableCell>
                            <TableCell class="font-semibold">
                                {{ score.name ?? "-" }}
                            </TableCell>
                            <TableCell class="font-semibold">
                                {{ score.total_value ?? "0" }}
                            </TableCell>
                        </TableRow>
                        <TableRow class="bg-gray-100">
                            <TableCell class="font-bold" colspan="2">
                                Total Nilai
                            </TableCell>
                            <TableCell class="font-bold">
                                {{
                                    student_program.report?.training
                                        ?.total_score ?? 0
                                }}
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[10px]">NO</TableHead>
                            <TableHead>PERTANDINGAN</TableHead>
                            <TableHead class="w-[10px]">NILAI</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="(score, index) in student_program.report
                                ?.match_event?.scores"
                            :key="index"
                        >
                            <TableCell class="font-medium">{{
                                index + 1
                            }}</TableCell>
                            <TableCell class="font-semibold">
                                {{ score.name ?? "-" }}
                            </TableCell>
                            <TableCell class="font-semibold">
                                {{ score.total_value ?? "0" }}
                            </TableCell>
                        </TableRow>
                        <TableRow class="bg-gray-100">
                            <TableCell class="font-bold" colspan="2">
                                Total Nilai
                            </TableCell>
                            <TableCell class="font-bold">
                                {{
                                    student_program.report?.match_event
                                        ?.total_score ?? 0
                                }}
                            </TableCell>
                        </TableRow>
                        <TableRow class="bg-gray-200">
                            <TableCell class="font-bold text-xl" colspan="2">
                                Grand Total
                            </TableCell>
                            <TableCell class="font-bold text-xl">
                                {{ student_program.report?.final_score ?? 0 }}
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </MainContent>
    </AppLayout>
</template>
