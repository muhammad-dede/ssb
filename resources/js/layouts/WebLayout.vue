<script setup>
import Navbar from "./web-layout/Navbar.vue";
</script>

<template>
    <header>
        <Navbar />
    </header>
    <main>
        <slot />
    </main>
    <footer class="bg-gray-800 text-gray-400 py-12">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8"
        >
            <div>
                <h4 class="text-white font-semibold mb-4">Tentang Kami</h4>
                <p>
                    Akademi Sepak Bola SSB – membentuk generasi juara dengan
                    kualitas & karakter.
                </p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Kontak</h4>
                <p>Email: info@akademiku.id</p>
                <p>Telepon: +62 812-3456-7890</p>
                <p>Alamat: Jl. Sepakbola No. 10, Kota Olahraga</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Ikuti Kami</h4>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-white">Facebook</a>
                    <a href="#" class="hover:text-white">Instagram</a>
                    <a href="#" class="hover:text-white">YouTube</a>
                </div>
            </div>
        </div>
        <div class="text-center text-sm text-gray-500 mt-8">
            © 2025 Akademi Sepak Bola. All rights reserved.
        </div>
    </footer>
</template>
