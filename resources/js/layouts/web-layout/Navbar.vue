<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import { Menu, X } from "lucide-vue-next";
import AppLogoIcon from "@/components/AppLogoIcon.vue";

const page = usePage();
const name = page.props.name;

const isOpen = ref(false);
const isScrolled = ref(false);

watch(isOpen, (val) => {
    if (val) {
        document.body.style.overflow = "hidden";
    } else {
        document.body.style.overflow = "";
    }
});

function toggleMenu() {
    isOpen.value = !isOpen.value;
}

function handleScroll() {
    isScrolled.value = window.scrollY > 10;
}

function handleResize() {
    if (window.innerWidth >= 768) {
        isOpen.value = false;
    }
}

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
    window.addEventListener("resize", handleResize);
    handleResize();
});

onBeforeUnmount(() => {
    window.removeEventListener("scroll", handleScroll);
    window.removeEventListener("resize", handleResize);
    document.body.style.overflow = "";
});
</script>

<template>
    <nav
        :class="[
            'fixed w-full z-50',
            isScrolled
                ? 'bg-white shadow-md py-4 transition-all duration-300'
                : 'bg-transparent py-4 md:py-10 transition-all duration-300',
            isOpen
                ? 'bg-white h-screen md:h-auto pointer-events-auto'
                : 'h-auto',
            isOpen ? 'overflow-y-auto' : 'overflow-y-visible',
        ]"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center min-h-11">
                <!-- Logo -->
                <div class="flex items-center w-1/6">
                    <Link :href="route('home')" class="flex items-center">
                        <AppLogoIcon
                            class="mr-2 size-8 fill-current text-green-600"
                        />
                        <span
                            :class="[
                                'text-lg font-bold',
                                isScrolled || isOpen
                                    ? 'text-green-600'
                                    : 'text-white',
                            ]"
                        >
                            {{ name }}
                        </span>
                    </Link>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a
                        href="#hero"
                        :class="[
                            'hover:text-green-800 font-medium',
                            isScrolled || isOpen
                                ? 'text-green-600'
                                : 'text-white',
                        ]"
                        >Beranda</a
                    >
                    <a
                        href="#about"
                        :class="[
                            'hover:text-green-800 font-medium',
                            isScrolled || isOpen
                                ? 'text-green-600'
                                : 'text-white',
                        ]"
                        >Tentang Kami</a
                    >
                    <a
                        href="#program"
                        :class="[
                            'hover:text-green-800 font-medium',
                            isScrolled || isOpen
                                ? 'text-green-600'
                                : 'text-white',
                        ]"
                        >Program</a
                    >
                    <a
                        href="#testimoni"
                        :class="[
                            'hover:text-green-800 font-medium',
                            isScrolled || isOpen
                                ? 'text-green-600'
                                : 'text-white',
                        ]"
                        >Testimoni</a
                    >
                </div>

                <!-- Auth Buttons -->
                <div
                    class="hidden md:flex items-center justify-end space-x-2 w-1/6"
                >
                    <Link
                        :href="route('login')"
                        :class="[
                            'font-semibold px-5 py-2 border-2 rounded-3xl transition',
                            isScrolled || isOpen
                                ? 'text-green-600 border-green-600 hover:bg-green-600 hover:text-white'
                                : 'text-white border-white hover:text-green-600 hover:bg-white',
                        ]"
                    >
                        Masuk
                    </Link>
                    <Link
                        :href="route('register')"
                        class="font-semibold px-5 py-2 border-2 border-green-600 rounded-3xl transition bg-green-600 text-white hover:bg-green-700 hover:border-green-700"
                    >
                        Daftar
                    </Link>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button
                        @click="toggleMenu"
                        :class="[
                            'focus:outline-none',
                            isScrolled || isOpen
                                ? 'text-green-600'
                                : 'text-white',
                        ]"
                    >
                        <Menu v-if="!isOpen" class="size-8" />
                        <X v-else class="size-8" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="isOpen" class="md:hidden px-5 py-8 space-y-10">
            <a
                href="#hero"
                @click="isOpen = false"
                class="block text-green-600 hover:text-green-800 font-medium"
                >Beranda</a
            >
            <a
                href="#about"
                @click="isOpen = false"
                class="block text-green-600 hover:text-green-800 font-medium"
                >Tentang Kami</a
            >
            <a
                href="#program"
                @click="isOpen = false"
                class="block text-green-600 hover:text-green-800 font-medium"
                >Program</a
            >
            <a
                href="#testimoni"
                @click="isOpen = false"
                class="block text-green-600 hover:text-green-800 font-medium"
                >Testimoni</a
            >
            <div class="grid space-y-5">
                <Link
                    :href="route('login')"
                    class="flex justify-center items-center font-semibold px-5 py-2 border-2 rounded-3xl transition text-green-600 border-green-600 hover:bg-green-600 hover:text-white"
                    @click="isOpen = false"
                >
                    Masuk
                </Link>
                <Link
                    :href="route('register')"
                    class="flex justify-center items-center font-semibold px-5 py-2 border-2 border-green-600 rounded-3xl transition bg-green-600 text-white hover:bg-green-700 hover:border-green-700"
                    @click="isOpen = false"
                >
                    Daftar
                </Link>
            </div>
        </div>
    </nav>
</template>
