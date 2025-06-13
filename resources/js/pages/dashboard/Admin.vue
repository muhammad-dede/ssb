<script setup>
import HeadingSmall from "@/components/HeadingSmall.vue";
import { Card, CardContent } from "@/components/ui/card/index";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table/index";
import {
    Alert,
    AlertDescription,
    AlertTitle,
} from "@/components/ui/alert/index";
import {
    ChartPie,
    ChartSpline,
    ChartColumnBig,
    ChartLine,
    Bell,
} from "lucide-vue-next";

defineProps({
    admin: Object,
});

const dateFormat = (dateStr) => {
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
};

const timeFormat = (time) => {
    const [hour, minute] = time.split(":");
    return `${hour}:${minute}`;
};
</script>

<template>
    <Alert class="mb-4">
        <Bell class="h-4 w-4" />
        <AlertTitle>Periode Aktif: </AlertTitle>
        <AlertDescription>
            {{ admin.period_active?.name ?? "Tidak ada periode aktif" }}
        </AlertDescription>
    </Alert>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-4">
        <Card>
            <CardContent class="relative">
                <div class="flex flex-col">
                    <h3 class="text-sm font-semibold mb-2">
                        Siswa Belum Terdaftar
                    </h3>
                    <span class="text-2xl font-bold mb-3">
                        {{ admin.count_student_unregistered }}
                    </span>
                    <small class="text-xs"
                        >Total Siswa yang belum melakukan pendaftaran</small
                    >
                    <div
                        class="absolute right-0 h-full flex items-start justify-center px-6"
                    >
                        <ChartPie class="size-8 text-yellow-500" />
                    </div>
                </div>
            </CardContent>
        </Card>
        <Card>
            <CardContent class="relative">
                <div class="flex flex-col">
                    <h3 class="text-sm font-semibold mb-2">Siswa Terdaftar</h3>
                    <span class="text-2xl font-bold mb-3">
                        {{ admin.count_student_registered }}
                    </span>
                    <small class="text-xs"
                        >Total Siswa yang sudah melakukan pendaftaran</small
                    >
                    <div
                        class="absolute right-0 h-full flex items-start justify-center px-6"
                    >
                        <ChartSpline class="size-8 text-green-500" />
                    </div>
                </div>
            </CardContent>
        </Card>
        <Card>
            <CardContent class="relative">
                <div class="flex flex-col">
                    <h3 class="text-sm font-semibold mb-2">Program Aktif</h3>
                    <span class="text-2xl font-bold mb-3">
                        {{ admin.count_program }}
                    </span>
                    <small class="text-xs">Total Program yang aktif</small>
                    <div
                        class="absolute right-0 h-full flex items-start justify-center px-6"
                    >
                        <ChartColumnBig class="size-8 text-cyan-500" />
                    </div>
                </div>
            </CardContent>
        </Card>
        <Card>
            <CardContent class="relative">
                <div class="flex flex-col">
                    <h3 class="text-sm font-semibold mb-2">Pelatih Aktif</h3>
                    <span class="text-2xl font-bold mb-3">
                        {{ admin.count_coach }}
                    </span>
                    <small class="text-xs">Total Pelatih yang aktif</small>
                    <div
                        class="absolute right-0 h-full flex items-start justify-center px-6"
                    >
                        <ChartLine class="size-8 text-violet-500" />
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
    <div class="grid lg:grid-cols-2 gap-4">
        <div>
            <HeadingSmall title="Jadwal Latihan Bulan Ini" />
            <div class="rounded-md border mb-4">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Tanggal</TableHead>
                            <TableHead>Waktu</TableHead>
                            <TableHead>Pelatih</TableHead>
                            <TableHead>Program</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="admin.training_schedules?.length > 0">
                            <TableRow
                                v-for="training in admin.training_schedules"
                                :key="training.id"
                            >
                                <TableCell>
                                    {{ dateFormat(training.training_date) }}
                                </TableCell>
                                <TableCell>
                                    {{ timeFormat(training.start_time) }} -
                                    {{ timeFormat(training.end_time) }}
                                </TableCell>
                                <TableCell>
                                    {{ training.coach?.name ?? "NA" }}
                                </TableCell>
                                <TableCell>
                                    {{ training.program_code }}
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow>
                                <TableCell
                                    :colspan="4"
                                    class="text-center py-6"
                                >
                                    <strong>Belum ada data</strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
        </div>
        <div>
            <HeadingSmall title="Jadwal Pertandingan Bulan Ini" />
            <div class="rounded-md border mb-4">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Tanggal</TableHead>
                            <TableHead>Waktu</TableHead>
                            <TableHead>Program</TableHead>
                            <TableHead>Lawan</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template
                            v-if="admin.match_event_schedules?.length > 0"
                        >
                            <TableRow
                                v-for="match_event in admin.match_event_schedules"
                                :key="match_event.id"
                            >
                                <TableCell>
                                    {{ dateFormat(match_event.match_date) }}
                                </TableCell>
                                <TableCell>
                                    {{ timeFormat(match_event.start_time) }} -
                                    {{ timeFormat(match_event.end_time) }}
                                </TableCell>
                                <TableCell>
                                    {{ match_event.program_code }}
                                </TableCell>
                                <TableCell>
                                    {{ match_event.opponent }}
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow>
                                <TableCell
                                    :colspan="4"
                                    class="text-center py-6"
                                >
                                    <strong>Belum ada data</strong>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
        </div>
    </div>
</template>
