<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import MainContent from "@/components/MainContent.vue";
import { Button } from "@/components/ui/button/index";
import {
    MoreHorizontal,
    Trash2,
    Pencil,
    Undo2,
    Bell,
    LoaderCircle,
} from "lucide-vue-next";
import HeadingGroup from "@/components/HeadingGroup.vue";
import Heading from "@/components/Heading.vue";
import { ref } from "vue";
import usePermissions from "@/composables/usePermissions";
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
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
    Alert,
    AlertDescription,
    AlertTitle,
} from "@/components/ui/alert/index";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import TabMatchEvent from "./show/TabMatchEvent.vue";
import TabAttendance from "./show/TabAttendance.vue";
import TabAssessment from "./show/TabAssessment.vue";

const { can } = usePermissions();

const props = defineProps({
    variants: Object,
    status_match_events: Object,
    attendances: Object,
    assessments: Object,
    match_event: Object,
    match_event_attendances: Object,
    match_event_assessments: Object,
});

const showConfirmDelete = ref(false);
const isGenerating = ref(false);

const destroy = () => {
    showConfirmDelete.value = false;
    router.delete(route("match-event.destroy", props.match_event?.id), {
        preserveScroll: true,
    });
};

const generate = () => {
    isGenerating.value = true;
    router.post(
        route("match-event.generate", props.match_event?.id),
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                isGenerating.value = false;
            },
        }
    );
};

const breadcrumbs = [
    { title: "Dashboard", href: "/dashboard" },
    { title: "Pertandingan", href: "/match-event" },
    { title: "Detail", href: "/match-event/show" },
];
</script>

<template>
    <Head title="Detail Pertandingan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <MainContent>
            <HeadingGroup>
                <Heading
                    title="Detail Pertandingan"
                    description="Informasi lengkap mengenai pertandingan yang terdaftar"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="w-8 h-8 p-0">
                            <MoreHorizontal class="w-4 h-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem
                            v-if="can('match-event.index')"
                            asChild
                        >
                            <Link :href="route('match-event.index')">
                                <Undo2 class="text-yellow-500" />
                                Kembali
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="can('match-event.edit')"
                            asChild
                        >
                            <Link
                                :href="
                                    route('match-event.edit', match_event.id)
                                "
                            >
                                <Pencil class="text-green-500" />
                                Ubah Data
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            v-if="can('match-event.delete')"
                            @select="showConfirmDelete = true"
                        >
                            <Trash2 class="text-red-500" />
                            Hapus Data
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </HeadingGroup>
            <Alert class="mb-2 relative">
                <Bell class="h-4 w-4" />
                <AlertTitle>Catatan</AlertTitle>
                <AlertDescription class="max-w-[50%]">
                    Klik generate untuk sinkronisasi data kehadiran dan
                    penilaian siswa.
                </AlertDescription>
                <div
                    class="absolute right-0 h-full flex items-center justify-center px-4"
                >
                    <Button
                        type="button"
                        class="w-fit"
                        @click.prevent="generate"
                        :disabled="isGenerating"
                    >
                        <LoaderCircle
                            v-if="isGenerating"
                            class="h-4 w-4 animate-spin mr-2"
                        />
                        <span>Generate</span>
                    </Button>
                </div>
            </Alert>
            <Tabs default-value="match_event" class="w-full">
                <TabsList class="grid w-full grid-cols-3">
                    <TabsTrigger value="match_event">Pertandingan</TabsTrigger>
                    <TabsTrigger value="attendances">Kehadiran</TabsTrigger>
                    <TabsTrigger value="assessments">Penilaian</TabsTrigger>
                </TabsList>
                <TabsContent value="match_event">
                    <TabMatchEvent
                        :variants="variants"
                        :status_match_events="status_match_events"
                        :match_event="match_event"
                    />
                </TabsContent>
                <TabsContent value="attendances">
                    <TabAttendance
                        :attendances="attendances"
                        :match_event="match_event"
                        :match_event_attendances="match_event_attendances"
                    />
                </TabsContent>
                <TabsContent value="assessments">
                    <TabAssessment
                        :assessments="assessments"
                        :match_event="match_event"
                        :match_event_assessments="match_event_assessments"
                    />
                </TabsContent>
            </Tabs>
        </MainContent>
    </AppLayout>

    <AlertDialog :open="!!showConfirmDelete">
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
                <AlertDialogCancel @click="showConfirmDelete = false">
                    Batal
                </AlertDialogCancel>
                <AlertDialogAction @click="destroy">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
