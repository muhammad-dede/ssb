<script setup>
import { Link } from "@inertiajs/vue3";
import WebLayout from "@/layouts/WebLayout.vue";
import HeroImage from "@/assets/images/hero.jpg";
import AboutImage from "@/assets/images/about.jpg";
import Testimonial1Image from "@/assets/images/testimonial1.jpg";
import Testimonial2Image from "@/assets/images/testimonial2.jpg";
import Testimonial3Image from "@/assets/images/testimonial3.jpg";
</script>

<template>
    <WebLayout>
        <!-- Hero -->
        <section id="hero" class="relative h-[75vh] md:h-screen">
            <img
                :src="HeroImage"
                alt="Hero Background"
                class="h-full w-full object-cover brightness-50"
            />
            <div
                class="absolute inset-0 flex items-end justify-start max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32"
            >
                <div class="text-white max-w-3xl space-y-4">
                    <h1 class="text-4xl md:text-6xl font-bold">
                        Latih Dirimu Seperti Juara, Bermainlah Seperti
                        Profesional
                    </h1>
                    <p class="text-lg md:text-xl">
                        Bergabunglah dengan akademi sepak bola tempat mimpi
                        menjadi kenyataan. Dari dasar hingga kejayaan,
                        perjalananmu dimulai di sini.
                    </p>
                    <div class="flex flex-col gap-4 sm:flex-row mt-4">
                        <Link
                            :href="route('register')"
                            class="px-6 py-3 bg-white text-green-600 rounded-3xl font-semibold flex justify-center items-center"
                        >
                            Bergabung ke Akademi
                        </Link>
                        <a
                            href="#program"
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-3xl font-semibold flex justify-center items-center"
                        >
                            Lihat Program
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section id="about" class="py-20 bg-gray-50">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center"
            >
                <div class="space-y-6">
                    <h2 class="text-3xl md:text-4xl font-bold text-green-600">
                        Tentang Akademi Kami
                    </h2>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Akademi Sepak Bola
                        <strong>SSB</strong> adalah tempat di mana semangat,
                        disiplin, dan impian bertemu dalam satu lapangan. Kami
                        berdedikasi untuk mengembangkan bakat muda Indonesia
                        melalui pelatihan yang terstruktur, pelatih profesional
                        bersertifikat, dan pendekatan yang menyenangkan namun
                        kompetitif.
                    </p>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Dengan program yang disesuaikan untuk berbagai jenjang
                        usia, kami membentuk pemain tidak hanya dari sisi
                        teknis, tetapi juga mental, kedisiplinan, dan kerja sama
                        tim. Baik pemula maupun calon atlet profesional, setiap
                        siswa akan mendapatkan pendampingan yang sesuai dengan
                        potensinya.
                    </p>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        Kami percaya bahwa setiap anak memiliki peluang untuk
                        bersinar. Di sinilah langkah pertama menuju masa depan
                        yang gemilang dimulai.
                    </p>
                </div>
                <div>
                    <img
                        :src="AboutImage"
                        alt="Tentang Kami"
                        class="w-full rounded-3xl shadow-lg object-cover"
                    />
                </div>
            </div>
        </section>

        <!-- Program -->
        <section id="program" class="bg-gray-100 py-20">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2
                        class="text-3xl md:text-4xl font-bold text-green-600 mb-4"
                    >
                        Program Unggulan
                    </h2>
                    <p
                        class="text-gray-600 text-lg md:text-xl max-w-3xl mx-auto"
                    >
                        Dirancang sesuai usia dan kemampuan, setiap program kami
                        memberikan pelatihan terbaik untuk membantu setiap
                        pemain berkembang maksimal, dari dasar hingga tingkat
                        profesional.
                    </p>
                </div>
                <div class="grid gap-10 md:grid-cols-3 px-4">
                    <div
                        class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition"
                    >
                        <h3 class="text-xl font-semibold text-green-600 mb-2">
                            Youth Program <br />(Usia 6–12)
                        </h3>
                        <p class="text-gray-600">
                            Pengenalan teknik dasar seperti passing, dribbling,
                            dan shooting dengan metode bermain yang
                            menyenangkan. Cocok untuk anak-anak yang baru mulai
                            belajar sepak bola.
                        </p>
                    </div>
                    <div
                        class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition"
                    >
                        <h3 class="text-xl font-semibold text-green-600 mb-2">
                            Teen Program <br />(Usia 13–17)
                        </h3>
                        <p class="text-gray-600">
                            Pendalaman teknik dan taktik, simulasi pertandingan,
                            dan latihan fisik yang lebih intensif. Cocok untuk
                            remaja yang ingin tampil lebih kompetitif.
                        </p>
                    </div>
                    <div
                        class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition"
                    >
                        <h3 class="text-xl font-semibold text-green-600 mb-2">
                            Adult Program <br />(Usia 18+)
                        </h3>
                        <p class="text-gray-600">
                            Persiapan fisik dan mental menuju level
                            semi-profesional atau kompetisi terbuka. Fokus pada
                            strategi lanjutan, evaluasi performa, dan liga
                            internal.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimoni" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <h2
                    class="text-3xl md:text-4xl font-bold text-green-600 text-center mb-12"
                >
                    Apa Kata Mereka
                </h2>
                <div class="grid gap-10 md:grid-cols-3">
                    <div class="bg-gray-50 p-6 rounded-xl shadow-md">
                        <p class="text-gray-600 mb-4">
                            “Akademi ini membantu anak saya tumbuh percaya diri
                            dan mahir bermain bola!”
                        </p>
                        <div class="flex items-center space-x-4">
                            <img
                                :src="Testimonial1Image"
                                alt="Orang Tua 1"
                                class="w-12 h-12 rounded-full object-cover"
                            />
                            <div>
                                <p class="font-semibold">Budi Santoso</p>
                                <p class="text-sm text-gray-500">
                                    Orang Tua, Program Youth
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-md">
                        <p class="text-gray-600 mb-4">
                            “Saya merasakan peningkatan mental kompetitif sejak
                            mengikuti Program Teen.”
                        </p>
                        <div class="flex items-center space-x-4">
                            <img
                                :src="Testimonial2Image"
                                alt="Siswa 2"
                                class="w-12 h-12 rounded-full object-cover"
                            />
                            <div>
                                <p class="font-semibold">Andi Wijaya</p>
                                <p class="text-sm text-gray-500">
                                    Siswa, Program Teen
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-md">
                        <p class="text-gray-600 mb-4">
                            “Program Adult menyiapkan saya berani di lapangan
                            dan mengasah taktik.”
                        </p>
                        <div class="flex items-center space-x-4">
                            <img
                                :src="Testimonial3Image"
                                alt="Siswa 3"
                                class="w-12 h-12 rounded-full object-cover"
                            />
                            <div>
                                <p class="font-semibold">Siti Nurhaliza</p>
                                <p class="text-sm text-gray-500">
                                    Siswa, Program Adult
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="bg-green-600 py-16">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Siap Mewujudkan Mimpi? 🎯
                </h2>
                <p class="text-lg text-green-100 mb-8">
                    Bergabung sekarang dan mulailah perjalanan sepak bolamu
                    bersama kami.
                </p>
                <Link
                    :href="route('register')"
                    class="px-6 py-3 bg-white text-green-600 rounded-3xl font-semibold hover:bg-gray-100 transition"
                >
                    Bergabung ke Akademi
                </Link>
            </div>
        </section>
    </WebLayout>
</template>
