<script setup>
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Button } from "@/components/ui/button";
import { ListFilter } from "lucide-vue-next";
import { computed, ref, watch } from "vue";

const props = defineProps({
    perPage: {
        type: [String, Number],
        default: 5,
    },
    perPages: {
        type: Array,
        default: () => [5, 10, 25, 50, 100],
    },
    filter: {
        type: String,
        default: "",
    },
    filters: {
        type: Array,
        default: () => [
            { label: "Terbaru", value: "desc" },
            { label: "Paling Lama", value: "asc" },
        ],
    },
    showLabel: {
        type: String,
        default: "Show",
    },
    entriesLabel: {
        type: String,
        default: "Entries",
    },
    filterLabel: {
        type: String,
        default: "Filter",
    },
});

const emit = defineEmits(["update:perPage", "update:filter"]);

const handlePerPageChange = (value) => {
    emit("update:perPage", parseInt(value));
};

const handleFilterChange = (value) => {
    internalFilter.value = value;
    emit("update:filter", value);
};

const currentPerPage = computed(() => props.perPage.toString());
const currentFilter = computed(() => props.filter);

const internalFilter = ref(props.filter);

watch(
    () => props.filter,
    (newValue) => {
        internalFilter.value = newValue;
    },
    { immediate: true }
);
</script>

<template>
    <div class="flex gap-4">
        <!-- Per Page Selector -->
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold">{{ showLabel }}</span>
            <Select
                :key="currentPerPage"
                :default-value="currentPerPage"
                @update:model-value="handlePerPageChange"
            >
                <SelectTrigger class="w-auto">
                    <SelectValue />
                </SelectTrigger>
                <SelectContent align="end">
                    <SelectGroup>
                        <SelectItem
                            v-for="(item, index) in perPages"
                            :key="index"
                            :value="item.toString()"
                        >
                            {{ item }} {{ entriesLabel }}
                        </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>

        <!-- Filter Dropdown -->
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button variant="outline" class="gap-2">
                    <ListFilter class="h-4 w-4" />
                    {{ filterLabel }}
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuRadioGroup
                    :model-value="internalFilter"
                    @update:model-value="handleFilterChange"
                >
                    <DropdownMenuRadioItem
                        v-for="(filterOption, index) in filters"
                        :key="index"
                        :value="filterOption.value"
                    >
                        {{ filterOption.label }}
                    </DropdownMenuRadioItem>
                </DropdownMenuRadioGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>
